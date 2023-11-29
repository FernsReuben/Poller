from flask import Flask,render_template, request
from flask_mysqldb import MySQL
 
app = Flask(__name__)
 
app.config['MYSQL_HOST'] = 'localhost'
app.config['MYSQL_USER'] = 'wreed6'
app.config['MYSQL_PASSWORD'] = 'x'
app.config['MYSQL_DB'] = 'wreed6'
 
mysql = MySQL(app)
 
@app.route('/')
 
def displayFaculty():
        cursor = mysql.connection.cursor()
        cursor.execute("SELECT * from instructor")
        mysql.connection.commit()
        data = cursor.fetchall()
        cursor.close()
        print(data)
        #return f"Done!! Query Result is {data}"
        return render_template('results.html', data=data)
 
app.run(host='localhost', port=5000)
