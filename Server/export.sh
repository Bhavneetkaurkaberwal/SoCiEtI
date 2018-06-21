mongoexport --db test --collection contents --csv --fields _id,_type,abuse_flaggers,anonymous,anonymous_to_peers,at_position_list,author_id,body,closed,comment_count,comment_thread_id,commentable_id,course_id,created_at,endorsed,historical_abuse,last_activity_at,parent_id,parents_id,sk,thread_type,title,updated_at,visible,votes --out $1.csv --port 27017 -u "admin" -p "admin123" --authenticationDatabase "admin"


