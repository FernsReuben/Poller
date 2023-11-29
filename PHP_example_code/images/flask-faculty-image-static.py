from flask import Flask,render_template, request, send_file
from flask_mysqldb import MySQL
import os


 
app = Flask(__name__)
 
app.config['MYSQL_HOST'] = 'localhost'
app.config['MYSQL_USER'] = 'wreed6'
app.config['MYSQL_PASSWORD'] = '7wfMoaT7'
app.config['MYSQL_DB'] = 'wreed6'
# used to check if an image file exists
@app.context_processor
def handle_context():
    return dict(os=os)
    
mysql = MySQL(app)
 
@app.route('/')
 
def displayFaculty():
        cursor = mysql.connection.cursor()
        cursor.execute("SELECT ID, NAME, dept_name, salary from instructor")
        mysql.connection.commit()
        data = cursor.fetchall()
        mysql.connection.commit()
        photo = cursor.fetchall()
        cursor.close()
        print(data)
        #return f"Done!! Query Result is {data}"
        return render_template('photo-results-static.html', data=data)


app.run(host='localhost', port=5000)
