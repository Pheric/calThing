import mysql.connector as mariadb
import sys
import random # for PoC category gen

# connect to the actual database
try:
    dbConnection = mariadb.connect(
        host = '35.188.71.139', 
        user='calThing', 
        password='jumpingTacos!!', 
        database='activitiesPost')
    cursor = dbConnection.cursor()
except:
    print("Could not connect to database.")
    exit(1)

# open the output file
inputStream = open(sys.argv[1], "r")

# get number of events
numEvents = int(inputStream.readline())
for i in range(0, numEvents):
    # grab event details
    eventCategory = random.randint(1, 5) # random for PoC
    eventName = inputStream.readline().strip()
    eventDesc = inputStream.readline().strip()
    eventWhen = inputStream.readline().strip()
    eventWhere = inputStream.readline().strip()
    eventContact = inputStream.readline().strip() # needed but not added to DB
    # add event to DB
    cursor.execute("INSERT INTO events (postId, categoryId, eventName, eventDescription, eventLocation, eventTime) VALUES (%s,%s,%s,%s,%s,%s)", (1, eventCategory, eventName, eventDesc, eventWhere, eventWhen))