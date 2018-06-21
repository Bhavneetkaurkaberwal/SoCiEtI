
# coding: utf-8

# In[33]:


import pandas as pd
import numpy as np
import sys
import MySQLdb as my
import os


def createFolder(directory):
    try:
        if not os.path.exists(directory):
            os.makedirs(directory)
    except OSError:
        print ('Error: Creating directory. ' +  directory)


path = sys.argv[1]
bson_file = sys.argv[2]
#df = pd.read_csv('/home/purav/Downloads/contents.csv', sep=',')
df = pd.read_csv(path, sep=',')
course = list(df['course_id'].unique())

# In[34]:
db = my.connect(host="127.0.0.1",
user="root",
passwd="",
db="mooc"
)
 

cursor = db.cursor()
primary_user_id="null"
i=0
while i < len(course):
    print('done'+str(i))
    df1=course[i]
    df2=course[i].replace("/", "_")
    
    sql = "INSERT INTO csv_repository VALUES('%s', '%s', '%s', '%s') " % \
            (df2, bson_file, path, primary_user_id)
    num = cursor.execute(sql)
    db.commit()
    print('done df'+str(i)+'=='+df1)
    i=i+1
#db.close()



