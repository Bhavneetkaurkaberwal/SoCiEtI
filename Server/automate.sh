#restore the uploaded bson in mongodb
 mongorestore -d test -c contents /home/purav/Downloads/contents.bson --port 27017 -u "admin" -p "admin123" --authenticationDatabase "admin"

 #export the bson in csv format for analysis
 mongoexport --db test --collection contents --type=csv --fields _id,_type,abuse_flaggers,anonymous,anonymous_to_peers,at_position_list,author_id,author_username,body,closed,comment_count,comment_thread,commentable_id,course_id,created_at,endorsed,historical_abuse,last_activity_at,parent_id,parents_id,sk,thread_type,title,updated_at,visible,votes --out /home/purav/Downloads/contents.csv --port 27017 -u "admin" -p "admin123" --authenticationDatabase "admin"


