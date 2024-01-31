# userAPI
The API support the following operations: Create, Read, Update, and
Delete (CRUD) for users.

# Install
XAMPP https://www.apachefriends.org/download.html
# Install
Postman https://www.postman.com/downloads/ 

clone using git command  under htdocs 
command : git clone https://github.com/SujataBagli/userAPI.git

path : C:\xampp\htdocs

In Postman copy the link mention below. 

To Create User:
● Endpoint: /users (HTTP Method: POST)
=> http://localhost/userAPI/insertUser.php
● Request: JSON body containing user data (name, email).
=> {"firstName":"jamc","lastName":"dais","email":"jackd@gmail.com.com","phone":"1221267895"}
● Response: JSON response with the newly created user.
=> {
    "err": 0,
    "message": "user added successfully!",
    "userData": {
        "id": "4",
        "firstName": "jamc",
        "lastName": "dais",
        "email": "jackd@gmail.com.com",
        "phone": "1221267895"
    }
}

Get User by ID:
● Endpoint: /users/{id} (HTTP Method: GET)
=> http://localhost/userAPI/getUser.php?id=4
● Response: JSON response containing user information.
=> {
    "err": 0,
    "message": "User Fetched Successfully!",
    "userData": {
        "id": "4",
        "firstName": "jamc",
        "lastName": "dais",
        "email": "jackd@gmail.com.com",
        "phone": "1221267895"
    }
}

Update User:
● Endpoint: /users/{id} (HTTP Method: PUT)
=> http://localhost/userAPI/updateUser.php?id=1
● Request: JSON body containing user data to be updated (name, email).
=> {"email":"ddae@gmail.com"}
● Response: JSON response with the updated user information.
=> {
    "err": 0,
    "message": "User data updated successfully!",
    "users": 
        {
            "id": "1",
            "firstName": "siya",
            "lastName": "bag",
            "email": "ddae@gmail.com",
            "phone": "1234567895"
        }
}
