from flask import Flask,render_template, request
from flask_mysqldb import MySQL
 
app = Flask(__name__)
 
app.config['MYSQL_HOST'] = 'localhost'
app.config['MYSQL_USER'] = 'wreed6'
app.config['MYSQL_PASSWORD'] = 'x'
app.config['MYSQL_DB'] = 'wreed6'
 
mysql = MySQL(app)
 
@app.route('/form')
def form():
    return render_template('form.html')
 
@app.route('/search', methods = ['POST', 'GET'])
def search():
    if request.method == 'GET':
        return "Fill out the Search Form"
     
    if request.method == 'POST':
        name = request.form['name']
        id = request.form['id']
        cursor = mysql.connection.cursor()
        if name:
            cursor.execute("SELECT * from instructor where name = %s",[name])
        if id:
            cursor.execute("SELECT * from instructor where ID = %s",[id])
        mysql.connection.commit()
        data = cursor.fetchall()
        cursor.close()
        print(data)
        #return f"Done!! Query Result is {data}"
        return render_template('results.html', data=data)
 
app.run(host='localhost', port=5000)
