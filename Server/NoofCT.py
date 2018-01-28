import pandas
import matplotlib.pyplot as plt
import datetime
from pandas import DataFrame
df=pandas.read_csv(r'C:\Users\DELL\Downloads\be project\result.csv', sep=',') #csv file saved in data frame

c=0     #counter for comments
ct=0    #counter for commentThreads

type = df['_type'] 

for i in type:
    if i == "Comment": #check whether type is comment or commentThread
        c=c+1
    else:
        ct=ct+1
		
print (c)
print (ct)

plt.pie([c,ct], labels=["Comment", "CommentThread"], autopct='%.2f', colors=['r', 'g']) #plot piechart with labels
plt.show()

