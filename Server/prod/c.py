
# coding: utf-8

# In[ ]:



import pandas as pd
import numpy as np
import sys
import MySQLdb as my
import os
import csv
import datetime
from datetime import datetime, timedelta
from dateutil.parser import parse
import math
from textblob import TextBlob
reload(sys)  
sys.setdefaultencoding('utf8')



def createFolder(directory):
    try:
        if not os.path.exists(directory):
            os.makedirs(directory)
    except OSError:
        print ('Error: Creating directory. ' +  directory)



db = my.connect(host="127.0.0.1",
user="root",
passwd="",
db="mooc"
)
cursor = db.cursor()
name = sys.argv[1]
#name = "course-v1:IITBombayX+ET611Tx+2017T2"
course=list()
course = name.split(",")
count=0
print(course)
while count < len(course):
    df1 = course[count]
    print(count, df1)
    createFolder('/opt/lampp/htdocs/project/prod/csvfiles/'+df1+'/')
    
    sql = "SELECT start_date FROM courses WHERE course_id='%s'" %(df1)
    cursor.execute(sql)
    strt = cursor.fetchone()
    strt = strt[0]

    sql = "SELECT end_date FROM courses WHERE course_id='%s'" %(df1)
    cursor.execute(sql)
    end = cursor.fetchone()
    end = end[0]
    
    sql = "SELECT path FROM csv_repository WHERE course_id='%s'" %(df1)
    cursor.execute(sql)
    path = cursor.fetchone()
    path=path[0]
    
    df = pd.read_csv(path, sep=',')
    df = df[df.course_id==df1.replace("_", "/")]
    df=df.sort_values('created_at')
    df['created_at'] = [parse(x) for x in df['created_at']]
    main = df[(df['created_at'] > strt) & (df['created_at'] <= end)].to_csv('/opt/lampp/htdocs/project/prod/csvfiles/'+df1+'/'+df1+'.csv',mode = 'w', index=False)
    pre = df[(df['created_at'] < strt)].to_csv('/opt/lampp/htdocs/project/prod/csvfiles/'+df1+'/pre'+df1+'.csv',mode = 'w', index=False)
    post = df[(df['created_at'] > end)].to_csv('/opt/lampp/htdocs/project/prod/csvfiles/'+df1+'/post'+df1+'.csv',mode = 'w', index=False)

   
    df2=course[count].replace("/", "_")
    path='/opt/lampp/htdocs/project/prod/csvfiles/'+df2+'/'
    gf = pd.read_csv(path+df2+'.csv', sep=',')
    
    #pie-chart csv
    c=0         #counter for comments
    ct=0        #counter for commentThreads
    r=0         #counter for replies
    
    #Summarizing data for db entry
    tot_learners = str(len(gf['author_id'].unique()))
    tot_discussion = 'Threads:'+str(ct)+', Comments:'+str(c)+', Replies:'+str(r)
    
    for _type,parent_id  in zip(gf._type, gf.parent_id): #print (_type,sk)
        if pd.isnull(parent_id):
            if _type == "CommentThread":#check whether type is comment or commentThread
                ct=ct+1
            else:
                c=c+1
        else:
            r=r+1
    pf=pd.DataFrame()
    
    #Calculating total_learners & total_discussion  for course_summary db.
    tot_learners = str(len(gf['author_id'].unique()))
    tot_discussion = 'Threads:'+str(ct)+', Comments:'+str(c)+', Replies:'+str(r)
    
    
    pf['type']=["Comment","CommentThread","Reply"]
    pf['value']=[c,ct,r]
    pf = pf.reset_index()
    pf = pf.drop('index', axis=1)
    pf.to_csv(path+df1.replace("/", "_")+'piechart.csv',mode = 'w', index=False) #csv generated
    


    
    #barchart
    #ddf = pd.read_csv('/opt/lampp/htdocs/project/prod/csvfiles/TISS_SKANI101x_2015-16/TISS_SKANI101x_2015-16.csv',sep = ',')
    ddf=pd.DataFrame()
    ddf=gf  #using separate dataframe from the one used to read actual file
    
    #excluding author_id
    ddf = ddf[ddf.author_id != 110640]
    ddf = ddf.reset_index()
    ddf = ddf.drop('index', axis=1)
    
    #only those fileds which are required for bargraph generation
    ddf=ddf[['_type','author_id','created_at']].sort_values('created_at')
    gf=gf.sort_values('created_at')
    
    object = list(ddf['created_at'])

    #extracting date from created_at field
    for i in range(0,len(object)):
        object[i] = object[i][:10]

    #converting string to datetime type
    object = [parse(x) for x in object]

    #date on which the first thread was created is stored in 'a'
    a = object[0]

    # calculating difference in no.of days of each thread created from the first one 
    day = list()
    for i in range(0,len(object)):
        b = object[i] - a
        day.append(b.days)

    # calculating week based on difference in days
    # if comment is created on the first day itseld difference will be zero so assing 1st week to it
    week = list()
    for i in range(0,len(object)):
        b =int((math.ceil(float(str(day[i]))/7)))
        if(b==0):
            b=1
        week.append(b)

    ddf['week'] = week                         #creating a week column in dataframe

    uw=list();
    uw=ddf['week'].unique()                    #list containing unique weeks
    uwlen = len(uw)
    
    w=list();
    w=ddf['week']                              #list containing week_numbers
    wlen = len(w)
    
    aid=list();
    aid=ddf['author_id']    
    
    ty=list();
    ty=ddf['_type']
    
    c=list();
    ct=list();
    
    for i in range(0,wlen):
        if ty[i]=='Comment':
            c.append(aid[i])                 #list of authors that have created a Comment
        elif ty[i]=='CommentThread':
            ct.append(aid[i])                #list of authors that have created a CommentThread

    ctc=list();
    ctc=set(c).intersection(ct)  #list of authors that have created a Comment & CommentThread
    
    #course_summary entry in database
    sql = "INSERT INTO course_summary VALUES('%s', '%s', '%s', '%s', '%s') " % \
            (df2, tot_learners, str(len(ctc)), tot_discussion, str(uwlen))
    num = cursor.execute(sql)
    db.commit()
    
    
    
    p=[0]*uwlen
    q=[0]*uwlen
    
    #loop for calculating active users (1 CommentThread + Comment) in each week
    for j in range(0,uwlen):
        for i in range(0,wlen):
            if w[i]==uw[j]:
                if aid[i] in ctc:
                    p[j]=p[j]+1
    
    #loop for calculating non-active users in each week.
    for j in range(0,uwlen):
        for i in range(0,wlen):
            if w[i]==uw[j]:
                if aid[i] not in ctc:
                    q[j]=q[j]+1

    z=list()
    y=list()

    # few weeks in between have no discussion at all, so we discard those weeks
    for i in range(0, len(p)):
        if p[i]!=0 or q[i]!=0:
            z.append(p[i])
            y.append(q[i])
        
    #dataframe for barchart.csv
    tf=pd.DataFrame()  
    b=list()
    for j in range(0,uwlen):
        a=uw[j]
        b.append('Week '+str(a))
    tf['weeks']=b
    tf['active']=p
    tf['nonactive']=q
    
    print("barchart")
    tf = tf.reset_index()
    tf = tf.drop('index', axis=1)
    tf.to_csv(path+df1.replace("/", "_")+'barchart.csv',mode = 'w', index=False)
    print('barchart df'+str(i)+'=='+df1)
    
    
    #week-wise csv for each course
    gf['week'] = ddf['week']  #appending weeks in gf dataframe
    i=0
    while(i<uwlen):
        a = uw[i]
        print('week'+str(i))
        gf[gf.week==a].to_csv(path+df1.replace("/", "_")+'week'+str(a)+'.csv',mode = 'w', index=False)
        print('week df'+str(i))
        i=i+1
    
    #transition graph
    nf=pd.DataFrame()

    a=list()
    b=list()
    a=gf['author_id'].unique()
    for i in range(0,len(a)):
        b.append(a[i])
    b.append(0)
    nf['name']=b

    aid=list()

    aid=gf['author_id'].unique()

    qf=pd.DataFrame()
    qf['name']=b


    max_value=0
    for i in range(0,len(uw)):
        x=uw[i]
        pf=pd.read_csv(path+df1.replace("/", "_")+'week'+str(x)+'.csv', sep=',')
        for i in range(0,len(aid)):
            c=0         
            ct=0
            r=0
            for _type,parent_id,author_id  in zip(pf._type, pf.parent_id, pf.author_id): 
                if author_id == aid[i]:
                    if pd.isnull(parent_id):
                        if _type == "CommentThread":
                            ct=ct+1
                        else:
                            c=c+1
                    else:
                        r=r+1
            t=c+ct+r
            if(max_value<t):
                max_value=t
    print(max_value)
    round_off=max_value%10
    max_value=max_value+(10-round_off)
    print(max_value)

    t=list()
    target=list()
    
    for i in range(0,len(uw)):
        x=uw[i]
        pf=pd.read_csv(path+df1.replace("/", "_")+'week'+str(x)+'.csv', sep=',')
        for i in range(0,len(aid)):
            c=0         
            ct=0
            r=0
            for _type,parent_id,author_id  in zip(pf._type, pf.parent_id, pf.author_id): 
                if author_id == aid[i]:
                    if pd.isnull(parent_id):
                        if _type == "CommentThread":
                            ct=ct+1
                        else:
                            c=c+1
                    else:
                        r=r+1
            t.append(c+ct+r)
        for i in range(0,len(t)):
            target.append(t[i])
        target.append(max_value)
        qf['week'+str(x)]=target
        t[:]=[]
        target[:]=[]   

    #print('transition'+str(i))
    qf.to_csv(path+df1.replace("/", "_")+'transitions.csv',mode = 'w', index=False)
    print('transition df'+str(i))
    






 
    
    #trends.csv
    trf = pd.read_csv(path+df1.replace("/", "_")+'transitions.csv', sep=',')
    trf=trf[trf.name!=0]
    n1=list()
    n2=list()
    n3=list()
    n4=list()
    n1=[0]*len(uw)
    n2=[0]*len(uw)
    n3=[0]*len(uw)
    n4=[0]*len(uw)

    weeks=list()
    for i in range(0,len(uw)):
        x=uw[i]
        week=list()
        week=trf['week'+str(x)]
        weeks.append('week'+str(x))
        for j in range(0,len(week)):
            if week[j]==0:
                n1[i]=n1[i]+1
            elif week[j]>0 and week[j]<5:
                n2[i]=n2[i]+1
            elif week[j]>4 and week[j]<10:
                n3[i]=n3[i]+1
            elif week[j]>9:
                n4[i]=n4[i]+1

    
    nf=pd.DataFrame()

    nf['weeks']=weeks
    nf['n1']=n1
    nf['n2']=n2
    nf['n3']=n3
    nf['n4']=n4
    #nf.to_csv(r'C:\xampp\htdocs\d3\course-v1_IITBombayX+ET611Tx+2017T1trends.csv')
    nf=nf.reset_index()
    nf=nf.drop('index',axis=1)
    nf.to_csv(path+df1.replace("/", "_")+'trends.csv',mode = 'w', index=False)
    
    
    
    

    #n/w graph for week-wise courses
    for j in range(0,len(uw)):
        #storing one  weeks in d 
        d=uw[j]
        a=str(d)
        
        #initializing lists
        source=list()
        target=list()
        sk=list()
        author_id=list()
        _id=list()
        comment_thread_id=list()
        skr=list()
        ske=list()
        skrv=list()
        type=list()
        id=list()
        comment=list()
        commentthread=list()
        reply=list()
        
        #mf= pd.read_csv('/opt/lampp/htdocs/project/prod/csvfiles/IITBombayX_IITBombayX_2015_2016/IITBombayX_IITBombayX_2015_2016week'+a+'.csv', sep=',')
        #reading week-wise csv
        mf = pd.read_csv(path+df1.replace("/", "_")+'week'+str(a)+'.csv')

        source=mf['author_id']


        gf['_id'] = gf['_id'].str.replace("ObjectId","")
        author_id=gf['author_id']
        _id=gf['_id']


        for k in range(0,len(_id)):
            _id[k] = _id[k].replace('(', '').replace(')', '')


        map(str.strip,_id)
        mf['sk'] = mf['sk']
        
        boo = mf['comment_thread_id'].isnull().values.all()
        
        if(boo == True):
            continue
            
        
        mf['comment_thread_id'] = mf['comment_thread_id']


        sk=mf['sk']
        
        
        comment_thread_id=mf['comment_thread_id'].str.replace("ObjectId","")



        cti = list(mf['comment_thread_id'].isnull())
        cti


        for k in range(0,len(comment_thread_id)):
            if cti[k] == False:
                comment_thread_id[k] = comment_thread_id[k].replace('(', '').replace(')', '')



        map(str.strip,str(comment_thread_id))


        new = mf['sk'].str.extract("(?P<part1>.*?)-(?P<part2>.*)")



        skr=list()
        skr=new['part1']

        ske = list(mf['sk'].isnull())


        skrv=list(new['part1'].isnull())

        ske_len = len(ske)
        for k in range (0,ske_len):
            target.append(-1)
        for m in range(0,ske_len):
            if ske[m]==True:
                target[m]=0
        for m in range(0,ske_len):
            if ske[m]==False and skrv[m]==True:
                for n in range(0,len(_id)):
                    if comment_thread_id[m]==_id[n]:
                        target[m] = author_id[n]
            if ske[m]==False and skrv[m]==False:
                for k in range(0,len(_id)):
                        if skr[m]==_id[k]:
                            target[m]=author_id[k]       


        nf=pd.DataFrame()
        nf['source']=source
        nf['target']=target

        nf['target']=nf['target'].replace('0','null')
        for _type,parent_id in zip(mf._type, mf.parent_id): 
            if pd.isnull(parent_id):
                if _type == "CommentThread":
                    type.append('type1')
                else:
                    type.append('type2')
            else:
                type.append('type3')
        nf['type']=type
        id[:]=[]
        id=mf['author_id']
        for m in range(0,len(id)):
            c=0         
            ct=0
            r=0
            for _type,parent_id,author_id  in zip(mf._type, mf.parent_id, mf.author_id): 
                if author_id == id[m]:
                    if pd.isnull(parent_id):
                        if _type == "CommentThread":
                            ct=ct+1
                        else:
                            c=c+1
                    else:
                        r=r+1
            comment.append(c)
            commentthread.append(ct)
            reply.append(r)
        nf['comment']=comment
        nf['commentthread']=commentthread
        nf['reply']=reply
        print('n/w '+str(a))
        nf.to_csv(path+df1.replace("/", "_")+'nwgraphweek'+str(a)+'.csv',mode = 'w', index=False)
        print(df1+'n/w '+str(a))
       



    #scatter graph
    sf=gf[['author_id','body']]

    body=list()
    aid=list()
    body=gf['body']
    aid=gf['author_id']

    polarity=list()
    subjectivity=list()


    for i in range(0,len(body)):
        sentence = body[i]
        blob = TextBlob(sentence)
        polarity.append(blob.sentiment.polarity)
        subjectivity.append(blob.sentiment.subjectivity)


    sf['polarity']=polarity
    sf['subjectivity']=subjectivity


    sf.to_csv(path+df1.replace("/", "_")+'scatter.csv',mode = 'w', index=False)
    print('scatter df'+str(i))

    q1aid=list()
    q1body=list()
    q1p=list()
    q1s=list()
    q2aid=list()
    q2body=list()
    q2p=list()
    q2s=list()


    for i in range(0,len(polarity)):
        if polarity[i]>=0:
            q1aid.append(aid[i])
            q1body.append(body[i])
            q1p.append(polarity[i])
            q1s.append(subjectivity[i])
        else:
            q2aid.append(aid[i])
            q2body.append(body[i])
            q2p.append(polarity[i])
            q2s.append(subjectivity[i])


    q1f=pd.DataFrame()
    q2f=pd.DataFrame()
    q1f['author_id']=q1aid
    q1f['body']=q1body
    q1f['polarity']=q1p
    q1f['subjectivity']=q1s
    q2f['author_id']=q2aid
    q2f['body']=q2body
    q2f['polarity']=q2p
    q2f['subjectivity']=q2s
    #q1f.to_csv(r'C:\xampp\htdocs\d3\FDP101xquadrant1scatter.csv')
    #q2f.to_csv(r'C:\xampp\htdocs\d3\FDP101xquadrant2scatter.csv')
    q1f.to_csv(path+df1.replace("/", "_")+'quad1.csv',mode = 'w', index=False)
    q2f.to_csv(path+df1.replace("/", "_")+'quad2.csv',mode = 'w', index=False)
    print('quad1 + quad2 df'+str(i))
    



    count = count + 1
   
db.close()

   



    

