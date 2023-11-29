from flask import Flask,render_template, request, send_file
from flask_mysqldb import MySQL
import io
import base64
 
app = Flask(__name__)
 
app.config['MYSQL_HOST'] = 'localhost'
app.config['MYSQL_USER'] = 'wreed6'
app.config['MYSQL_PASSWORD'] = '7wfMoaT7'
app.config['MYSQL_DB'] = 'wreed6'
 
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
        return render_template('photo-results.html', data=data)

@app.route('/i/<int:ident>')
def profile_image(ident):
    cursor = mysql.connection.cursor()
    cursor.execute("SELECT photo from instructor where ID = %s",[ident])
    result = cursor.fetchone() # There's only one
    image_bytes = result[0] # The first and only column
    #print(result[0])
    bytes_io = io.BytesIO(image_bytes)
    cursor.close()
    return send_file(bytes_io, mimetype='image/jpeg')  

app.run(host='localhost', port=5000)
