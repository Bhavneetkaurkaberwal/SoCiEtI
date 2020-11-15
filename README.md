# SoCiEtI (Social Cohesion Evaluator for MOOC Instructors)

A web application to analyze discussion forum data from MOOC platforms.

## ABOUT
The project is a part of 10 months research internship at IIT-Bombay. This is a web interface to gather information about social cohesion of participants in a Massive Open Online Course (MOOC) - [IITBombayX](https://www.iitbombayx.in/). The course platform is based on Open EdX Cypress Edition and cohesion is calculated strictly based on Discussion Forum participation. The system will assist the MOOC Administrator and course Instructor with information such as total registered learners, most active groups and learners, number of threads and comments, network graph of learners, trends and transition of learners across the weeks and MIS report.

The back-end is developed in PHP, front-end CSS, HTML and JS. Data anlaysis is done using python.  

## System Architecture 
The MOOC admin will login to the system and upload the Discussion dump (bson file) onto
the server and the bson dump will be converted into various CSV file depending upon their course_id. These files will be stored in a csv repository database and all the activities will be
reflected in the System logs. Admin can monitor overall system usage through system logs.
Database: It will map author_id with the course_id of which the logged in user is instructor of
and display analytical results of those courses onto the dashboard of respective Instructor.
CSV repository: It will contain all csv files mapped with their course_id and available for
instructors / researchers for download.

#### System Diagram
![](/Assets/SystemDiagram.jpg)

#### Class Diagram
![](/Assets/ClassDiagram.jpg)

#### Use Case Diagram
![](/Assets/UseCase.jpg)

## Results
Social cohesion calculated for an individual course are displayed using various graphical representations.
Click [here](/Assets/Results.pdf) to see an example.

## Documentation
For more detailed documentation click [here](/Assets/Documentation.pdf)