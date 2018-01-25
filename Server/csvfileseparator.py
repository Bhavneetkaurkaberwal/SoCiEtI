
# coding: utf-8

# In[33]:


import pandas as pd
import numpy as np
df = pd.read_csv('/home/purav/Downloads/contents.csv', sep=',')		# reads the csv 
course = list(df['course_id'].unique())								# identifies unique courses in the csv


# In[34]:


i=0
while i < len(course):
    df1=course[i]													
    df[df.course_id==df1].to_csv(df1.replace("/", "_")+'.csv')     #converts data of each course to a separate csvfile named as per the course_id.
    i=i+1

