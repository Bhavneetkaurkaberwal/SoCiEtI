
# coding: utf-8

# In[33]:


import pandas as pd
import numpy as np
import sys
import MySQLdb as my

path = sys.argv[1]
bson_file = sys.argv[2]
df = pd.read_csv('/home/purav/Downloads/data.csv', sep=',')
course = list(df['course_id'].unique())


# In[34]:
db = my.connect(host="127.0.0.1",
user="root",
passwd="",
db="mooc"
)
 
cursor = db.cursor()
user_id="null"
csv_id="null"

i=0
while i < len(course):
    print('done'+str(i))
    df1=course[i]
    df[df.course_id==df1].to_csv(df1.replace("/", "_")+'.csv')
    sql = "INSERT INTO csv_repository VALUES('%s', '%s', '%s', '%s', '%s') " % \
            (df1, bson_file, path, user_id, csv_id)
    num = cursor.execute(sql)
    db.commit()
    print('done df'+str(i)+'=='+df1)
    i=i+1
db.close()

