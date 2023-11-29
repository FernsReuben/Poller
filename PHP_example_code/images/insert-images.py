import mysql.connector
 
# Convert images or files data to binary format
def convert_data(file_name):
    with open(file_name, 'rb') as file:
        binary_data = file.read()
    return bytes(binary_data)
 
 
try:
    connection = mysql.connector.connect(host='localhost',
                                         database='wreed6',
                                         user='wreed6',
                                         password='7wfMoaT7')
    cursor = connection.cursor()
    # alter table query
    alter_table = """alter table instructor add photo longblob"""
 
    # Execute the create_table query first
    cursor.execute(alter_table)
    # printing successful message
    print("Table altered Successfully")
 
    query = """ UPDATE instructor set photo = %s where ID = %s"""
 
    # First Data Insertion
    instructor_id = "10101"
    profile_picture = convert_data("./photos/10101.jpg")
    
 
    # Inserting the data in database in tuple format
    result = cursor.execute(query, (profile_picture, instructor_id))
   
    # Second Data Insertion
    instructor_id = "45565"
    profile_picture = convert_data("./photos/45565.png")
    
    # Inserting the data in database in tuple format
    result = cursor.execute(query, (profile_picture, instructor_id))
   
   # 3rd Data Insertion
    instructor_id = "83821"
    profile_picture = convert_data("./photos/83821.jpg")
    
    # Inserting the data in database in tuple format
    result = cursor.execute(query, (profile_picture, instructor_id))
    
    # insert stick figure for everyone else
    query2 = """ UPDATE instructor set photo = %s where photo IS NULL"""
    profile_picture = convert_data("./photos/unknown.png")
    result = cursor.execute(query2, (profile_picture,))

    # Committing the data
    connection.commit()
    print("Successfully Inserted Values")
 
# Print error if occurred
except mysql.connector.Error as error:
    print(format(error))
 
finally:
   
    # Closing all resources
    if connection.is_connected():
       
        cursor.close()
        connection.close()
        print("MySQL connection is closed")