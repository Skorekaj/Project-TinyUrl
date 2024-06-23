import mysql.connector

cnx = mysql.connector.connect(user='root', password='jaas', host='ubuntu.local', database='test')
with cnx.cursor() as cursor:
	result = cursor.execute("SELECT * FROM Persons LIMIT 5")
	rows = cursor.fetchall()
	for row in rows:
		print(row)
		print(row[0],row[2])
	cnx.close()


