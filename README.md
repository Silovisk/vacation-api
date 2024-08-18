# Vacation Plan API Documentation

### Environment Setup POSTMAN

Create an environment in Postman with the following variables:

-   **Variable:** `BASE_URL`

    -   **Value:** `http://localhost/api` (or the base URL of your API)

-   **Variable:** `TOKEN`
    -   **Value:** (will be automatically set after login)

# Endpoints Authentication using Sanctum (Bearer Token)

### 1. Register User

-   **Endpoint:** `POST /register`
-   **Description:** Registers a new user.
-   **Request URL:** `http://127.0.0.1:8000/api/register`
-   **Request Method:** `POST`
-   **Authentication:** `Not Required`
-   **Controller Action** `AuthController@register`

#### Response Examples

##### Example 1: Register User (Success)

-   **Status:** 200 OK
-   **Request Body:**

```json
{
    "name": "test",
    "email": "test@example.com",
    "password": "test123",
    "c_password": "test123"
}
```

-   **Response:**

```json
{
    "success": true,
    "data": {
        "token": "1|BQ5Oig805mgGtLgW8K11bJt7YAHIiDqfbdzqvqjsc79b0512",
        "name": "test"
    },
    "message": "User registered successfully."
}
```

##### Error Response (Validation Error)

```json
{
    "success": false,
    "errors": {
        "name": ["The name field is required."],
        "email": [
            "The email field is required.",
            "The email must be a valid email address.",
            "The email has already been taken."
        ],
        "password": ["The password field is required."],
        "c_password": [
            "The c password field is required.",
            "The c password confirmation does not match."
        ]
    },
    "message": "Validation Error."
}
```

### 2. Login

-   **Endpoint:** `POST /login`
-   **Description:** Logs in an existing user.
-   **Request URL:** `http://127.0.0.1:8000/api/login`
-   **Request Method:** `POST`
-   **Authentication:** `Not Required`
-   **Controller Action** `AuthController@login`

#### Response Examples

##### Example 1: Login (Success)

-   **Status:** 200 OK
-   **Request Body:**

```json
{
    "email": "test@example.com",
    "password": "test123"
}
```

-   **Response:**

```json
{
    "success": true,
    "data": {
        "token": "3|3b5ibxmD427artIbOzS1HRdMYM3081YlyHNk28mD77392c5d",
        "name": "test"
    },
    "message": "User logged in successfully."
}
```

##### Example 2: Login (Invalid Credentials)

-   **Status:** 401 Unauthorized
-   **Request Body:**

```json
{
    "email": "testNotExisting@example.com",
    "password": "test123"
}
```

-   **Response:**

```json
{
    "success": false,
    "message": "Unauthorized"
}
```

### Storing Token

```txt
Script POSTMAN: Vacation Plan API/Auth/Storing Token
```

```javascript
// Get the BASE_URL environment variable
var baseUrl = pm.environment.get("BASE_URL");

var requestConfig = {
    method: "POST",
    url: baseUrl + "/login",
    header: "Content-Type: application/json",
    body: {
        mode: "raw",
        raw: JSON.stringify({
            email: "test@example.com",
            password: "test123",
        }),
    },
};

pm.sendRequest(requestConfig, function (err, response) {
    if (err) {
        console.error("Request error:", err);
    } else {
        if (response.code === 200) {
            var responseBody = response.json();
            var token = responseBody.data.token;
            pm.environment.set("TOKEN", token);
        } else {
            console.error("Failed to login. Response code:", response.code);
            console.error("Response body:", response.body);
        }
    }
});
```

# Endpoints Vacation Plan

### 1. Create a New Holiday Plan

-   **Endpoint:** `POST /vacation-plan`
-   **Description:** Creates a new holiday plan.
-   **Request URL:** `http://127.0.0.1:8000/api/vacation-plan`
-   **Request Method:** `POST`
-   **Authentication:** `Required`
-   **Controller Action** `VacationPlanController@store`

#### Response Examples

##### Example 1: date format error

-   **Status:** 422 Unprocessable Content
-   **Request Body:**

```json
{
    "title": "New Vacation Plan",
    "description": "Description of the vacation plan",
    "date": "2024-08-1",
    "location": "Destination City",
    "participants": ["Alice", "Bob", "Charlie", "Dave", "Eve"]
}
```

-   **Response:**

```json
{
    "form-errors": {
        "date": ["The date field must match the format Y-m-d."]
    }
}
```

##### Example 1: Unauthorized

-   **Status:** 401 Unauthorized
-   **Request Body:**

```json
{
    "title": "New Vacation Plan",
    "description": "Description of the vacation plan",
    "date": "2024-08-16",
    "location": "Destination City",
    "participants": ["Alice", "Bob", "Charlie", "Dave", "Eve"]
}
```

-   **Response:**

```json
{
    "message": "Unauthorized access. Please authenticate.",
    "error": true
}
```

##### Example 2: Unprocessable Entity (Missing Fields)

-   **Status:** 422 Unprocessable Content
-   **Request Body:**

```json
{}
```

-   **Response:**

```json
{
    "form-errors": {
        "title": ["The title field is required."],
        "description": ["The description field is required."],
        "date": ["The date field is required."],
        "location": ["The location field is required."]
    }
}
```

##### Example 3: Unprocessable Entity (Invalid Data Types)

-   **Status:** 422 Unprocessable Content
-   **Request Body:**

```json
{
    "title": 123,
    "description": 123,
    "date": "string",
    "location": 123,
    "participants": 123
}
```

-   **Response:**

```json
{
    "form-errors": {
        "title": ["The title field must be a string."],
        "description": ["The description field must be a string."],
        "date": ["The date field must be a valid date."],
        "location": ["The location field must be a string."],
        "participants": ["The participants field must be an array."]
    }
}
```

##### Example 4: Unprocessable Entity (Field Length Exceeded)

-   **Status:** 422 Unprocessable Content
-   **Request Body:**

```json
{
    "title": "A very long title exceeding the maximum length of 255 characters...exceeding the maximum length of 255 characters...exceeding the maximum length of 255 characters...exceeding the maximum length of 255 characters...exceeding the maximum length of 255 characters...exceeding the maximum length of 255 characters...exceeding the maximum length of 255 characters...exceeding the maximum length of 255 characters...exceeding the maximum length of 255 characters...exceeding the maximum length of 255 characters...exceeding the maximum length of 255 characters...exceeding the maximum length of 255 characters...exceeding the maximum length of 255 characters...exceeding the maximum length of 255 characters...exceeding the maximum length of 255 characters...exceeding the maximum length of 255 characters...",
    "description": "Description of the vacation plan",
    "date": "2024-08-16",
    "location": "A very long location name exceeding the maximum length of 255 characters...exceeding the maximum length of 255 characters...exceeding the maximum length of 255 characters...exceeding the maximum length of 255 characters...exceeding the maximum length of 255 characters...exceeding the maximum length of 255 characters...exceeding the maximum length of 255 characters...exceeding the maximum length of 255 characters...exceeding the maximum length of 255 characters...exceeding the maximum length of 255 characters...exceeding the maximum length of 255 characters...exceeding the maximum length of 255 characters...exceeding the maximum length of 255 characters...exceeding the maximum length of 255 characters...exceeding the maximum length of 255 characters...exceeding the maximum length of 255 characters...",
    "participants": ["Alice", "Bob", "Charlie", "Dave", "Eve"]
}
```

-   **Response:**

```json
{
    "form-errors": {
        "title": ["The title field must not be greater than 255 characters."],
        "location": [
            "The location field must not be greater than 255 characters."
        ]
    }
}
```

##### Example 5: Success

-   **Status:** 200 OK
-   **Request Body:**

```json
{
    "title": "New Vacation Plan",
    "description": "Description of the vacation plan",
    "date": "2024-08-16",
    "location": "Destination City",
    "participants": ["Alice", "Bob", "Charlie", "Dave", "Eve"]
}
```

-   **Response:**

```json
{
    "success": true,
    "data": {
        "id": 1,
        "title": "New Vacation Plan",
        "description": "Description of the vacation plan",
        "date": "2024-08-16",
        "location": "Destination City",
        "participants": ["Alice", "Bob", "Charlie", "Dave", "Eve"]
    },
    "message": "Vacation Plan create successfully."
}
```

### 2. Retrieve All Holiday Plans

-   **Endpoint:** `GET /vacation-plan`
-   **Description:** Retrieves a list of all holiday plans.
-   **Request URL:** `http://127.0.0.1:8000/api/vacation-plan`
-   **Request Method:** `GET`
-   **Authentication:** `Required`
-   **Controller Action** `VacationPlanController@index`

#### Response Examples

##### Example 1: Retrieve All Holiday Plans

-   **Request Body:**
-   **per_page** (optional): Number of items per page (default: 15)

```json
{
    "per_page": 5
}
```

-   **Response:**
-   **Status Code:** 200 OK

```json
{
    "data": [
        {
            "id": 1,
            "title": "Et et voluptatum quae aut necessitatibus.",
            "description": "Ipsa ullam natus repellendus beatae. Quas non excepturi quam nobis.",
            "date": "2017-05-22",
            "location": "Gradyfurt",
            "participants": ["Dave", "Charlie", "Alice", "Bob", "Eve"]
        },
        {
            "id": 2,
            "title": "Qui necessitatibus consequatur ut aut dicta aut.",
            "description": "Eos officia laborum dolores officiis. Ducimus ipsum quia neque quaerat voluptatem aspernatur et. Ut suscipit qui ducimus incidunt.",
            "date": "2019-10-25",
            "location": "Rosinaberg",
            "participants": ["Bob", "Alice", "Charlie", "Dave", "Eve"]
        },
        {
            "id": 3,
            "title": "Et sint tenetur impedit sint odio.",
            "description": "Quo repudiandae itaque non aliquid et et. Magni debitis qui aut voluptate et mollitia ipsum. Alias veniam non ut enim. Eligendi tempora iure sapiente sit provident.",
            "date": "2006-02-24",
            "location": "Pourosville",
            "participants": ["Charlie", "Bob", "Eve"]
        },
        {
            "id": 4,
            "title": "Aut nihil impedit distinctio omnis optio commodi veniam.",
            "description": "Quia voluptate quaerat porro quibusdam quis inventore ipsam. Et et ratione sit. Natus quos voluptates est voluptates.",
            "date": "2010-03-10",
            "location": "Joelbury",
            "participants": ["Eve"]
        },
        {
            "id": 5,
            "title": "Ipsa dicta doloribus consectetur eligendi magnam quam ea.",
            "description": "Praesentium neque qui maxime deleniti. Dolorum aut expedita qui ullam ducimus fuga perferendis repellendus. Dolorem voluptates cumque nesciunt consequuntur eos aut eos. Consequatur excepturi quos voluptas temporibus est. Ut qui corporis iure omnis iste voluptas dolores.",
            "date": "1973-04-27",
            "location": "West Sadyeport",
            "participants": ["Alice", "Charlie", "Eve"]
        }
    ],
    "links": {
        "first": "http://127.0.0.1:8000/api/vacation-plan?page=1",
        "last": "http://127.0.0.1:8000/api/vacation-plan?page=300",
        "prev": null,
        "next": "http://127.0.0.1:8000/api/vacation-plan?page=2"
    },
    "meta": {
        "current_page": 1,
        "from": 1,
        "last_page": 300,
        "links": [
            {
                "url": null,
                "label": "&laquo; Previous",
                "active": false
            },
            {
                "url": "http://127.0.0.1:8000/api/vacation-plan?page=1",
                "label": "1",
                "active": true
            },
            {
                "url": "http://127.0.0.1:8000/api/vacation-plan?page=2",
                "label": "2",
                "active": false
            },
            {
                "url": "http://127.0.0.1:8000/api/vacation-plan?page=3",
                "label": "3",
                "active": false
            },
            {
                "url": "http://127.0.0.1:8000/api/vacation-plan?page=4",
                "label": "4",
                "active": false
            },
            {
                "url": "http://127.0.0.1:8000/api/vacation-plan?page=5",
                "label": "5",
                "active": false
            },
            {
                "url": "http://127.0.0.1:8000/api/vacation-plan?page=6",
                "label": "6",
                "active": false
            },
            {
                "url": "http://127.0.0.1:8000/api/vacation-plan?page=7",
                "label": "7",
                "active": false
            },
            {
                "url": "http://127.0.0.1:8000/api/vacation-plan?page=8",
                "label": "8",
                "active": false
            },
            {
                "url": "http://127.0.0.1:8000/api/vacation-plan?page=9",
                "label": "9",
                "active": false
            },
            {
                "url": "http://127.0.0.1:8000/api/vacation-plan?page=10",
                "label": "10",
                "active": false
            },
            {
                "url": null,
                "label": "...",
                "active": false
            },
            {
                "url": "http://127.0.0.1:8000/api/vacation-plan?page=299",
                "label": "299",
                "active": false
            },
            {
                "url": "http://127.0.0.1:8000/api/vacation-plan?page=300",
                "label": "300",
                "active": false
            },
            {
                "url": "http://127.0.0.1:8000/api/vacation-plan?page=2",
                "label": "Next &raquo;",
                "active": false
            }
        ],
        "path": "http://127.0.0.1:8000/api/vacation-plan",
        "per_page": 5,
        "to": 5,
        "total": 1500
    }
}
```

##### Example 2: No Data Available

-   **Status:** 404 Not Found
-   **Request Body:**

```json
{
    "per_page": 5
}
```

-   **Response:**

```json
{
    "success": false,
    "message": "No vacation plan data available."
}
```

##### Example 3: Invalid per_page Value (Not an Integer)

-   **Status:** 422 Unprocessable Content
-   **Request Body:**

```json
{
    "per_page": "abc"
}
```

-   **Response:**

```json
{
    "form-errors": {
        "per_page": ["The per page must be an integer."]
    }
}
```

#### Example 4: Invalid per_page Value (Less than 1)

-   **Status:** 422 Unprocessable Content
-   **Request Body:**

```json
{
    "per_page": 0
}
```

-   **Response:**

```json
{
    "form-errors": {
        "per_page": ["The per page must be at least 1."]
    }
}
```

#### Example 5: Invalid per_page Value (Greater than 100)

-   **Status:** 422 Unprocessable Content
-   **Request Body:**

```json
{
    "per_page": 101
}
```

-   **Response:**

```json
{
    "form-errors": {
        "per_page": ["The per page may not be greater than 100."]
    }
}
```

### 3. Retrieve a Specific Holiday Plan by ID

-   **Endpoint:** `GET /vacation-plan/{id}`
-   **Description:** Retrieves a specific holiday plan by its ID.
-   **Request URL:** `http://127.0.0.1:8000/api/vacation-plan/{id}`
-   **Request Method:** `GET`
-   **Authentication:** `Required`
-   **Controller Action** `VacationPlanController@show`

#### Response Examples

#### Example 1: Retrieve a Specific Holiday Plan by ID (Success)

-   **Status:** 200 OK
-   **Request URL:**

```txt
GET: http://127.0.0.1:8000/api/vacation-plan/1
```

-   **Response:**

```json
{
    "success": true,
    "data": {
        "id": 1,
        "title": "New Vacation Plan",
        "description": "Description of the vacation plan",
        "date": "2024-08-16",
        "location": "Destination City",
        "participants": ["Alice", "Bob", "Charlie", "Dave", "Eve"]
    },
    "message": "Vacation plan retrieved successfully."
}
```

#### Example 2: Holiday Plan Not Found

-   **Status:** 404 Not Found
-   **Request URL:**

```txt
GET: http://127.0.0.1:8000/api/vacation-plan/0
```

-   **Response:**

```json
{
    "success": false,
    "message": "Vacation plan not found."
}
```

### 4. Update an Existing Holiday Plan

-   **Endpoint:** `PUT /vacation-plan/{id}`
-   **Description:** Updates an existing holiday plan by its ID.
-   **Request URL:** `http://127.0.0.1:8000/api/vacation-plan/{id}`
-   **Request Method:** `PUT` or `PATCH`
-   **Authentication:** `Required`
-   **Controller Action** `VacationPlanController@update`

-   **Note:** You can use either `PUT` or `PATCH` method to update a holiday plan. `PUT` method will replace the entire resource with the new data, while `PATCH` method will update only the specified fields.

#### Response Examples

#### Example 1: Update a Holiday Plan (Success) - PATCH Method

-   **Status:** 200 OK
-   **Request URL:**

```txt
PATCH: http://127.0.0.1:8000/api/vacation-plan/1
```

-   **Request Body:**

```json
{
    "title": "Updated Vacation Plan Title"
}
```

-   **Response:**

```json
{
    "success": true,
    "data": {
        "id": 1,
        "title": "Updated Vacation Plan Title",
        "description": "Description of the vacation plan",
        "date": "2024-08-16",
        "location": "Destination City",
        "participants": ["Alice", "Bob", "Charlie", "Dave", "Eve"]
    },
    "message": "Vacation Plan update successfully."
}
```

#### Example 2: Update a Holiday Plan (Success) - PUT Method

-   **Status:** 200 OK
-   **Request URL:**

```txt
PUT: http://127.0.0.1:8000/api/vacation-plan/1
```

-   **Request Body:**

```json
{
    "title": "Updated Vacation Plan Title",
    "description": "Updated Description of the vacation plan",
    "date": "2024-08-17",
    "location": "Updated Destination City",
    "participants": ["Frank"]
}
```

-   **Response:**

```json
{
    "success": true,
    "data": {
        "id": 1,
        "title": "Updated Vacation Plan Title",
        "description": "Updated Description of the vacation plan",
        "date": "2024-08-17",
        "location": "Updated Destination City",
        "participants": ["Frank"]
    },
    "message": "Vacation Plan update successfully."
}
```

#### Example 3: Update a Holiday Plan (Not Found)

-   **Status:** 404 Not Found
-   **Request URL:**

```txt
PUT: http://127.0.0.1:8000/api/vacation-plan/0
```

-   **Request Body:**

```json
{
    "title": "Updated Vacation Plan Title"
}
```

-   **Response:**

```json
{
    "success": false,
    "message": "Vacation plan not found."
}
```

#### Example 6: Invalid Request Body (Invalid Data Types)

-   **Status:** 422 Unprocessable Content
-   **Request URL:**

```txt
PUT: http://127.0.0.1:8000/api/vacation-plan/1
```

-   **Request Body:**

```json
{
    "title": 123,
    "description": 123,
    "date": "2024/08/17",
    "location": 123,
    "participants": "Frank"
}
```

-   **Response:**

```json
{
    "form-errors": {
        "title": ["The title field must be a string."],
        "description": ["The description field must be a string."],
        "date": ["The date field must match the format Y-m-d."],
        "location": ["The location field must be a string."],
        "participants": ["The participants field must be an array."]
    }
}
```

### 5. Delete a Holiday Plan

-   **Endpoint:** `DELETE /vacation-plan/{id}`
-   **Description:** Deletes a specific holiday plan by its ID.
-   **Request URL:** `http://127.0.0.1:8000/api/vacation-plan/{id}`
-   **Request Method:** `DELETE`
-   **Authentication:** `Required`
-   **Controller Action** `VacationPlanController@destroy`

#### Response Examples

#### Example 1: Delete a Holiday Plan (Success)

-   **Status:** 200 OK
-   **Request URL:**

```txt
DELETE: http://127.0.0.1:8000/api/vacation-plan/1
```

-   **Response:**

```json
{
    "success": true,
    "data": [],
    "message": "Vacation Plan delete successfully."
}
```

#### Example 2: Delete a Holiday Plan (Not Found)

-   **Status:** 404 Not Found
-   **Request URL:**

```txt
DELETE: http://127.0.0.1:8000/api/vacation-plan/0
```

-   **Response:**

```json
{
    "success": false,
    "message": "Vacation plan not found."
}
```

### 6. Generate PDF for a Specific Holiday Plan

-   **Endpoint:** `GET /vacation-plan/{id}/generate-pdf`
-   **Description:** Generates a PDF file for a specific holiday plan by its ID.
-   **Request URL:** `http://127.0.0.1:8000/api/vacation-plan/{id}/generate-pdf`
-   **Request Method:** `GET`
-   **Authentication:** `Required`
-   **Controller Action** `VacationPlanController@generatePdf`

#### Response Examples

#### Example 1: Generate PDF for a Specific Holiday Plan (Success)

-   **Status:** 200 OK
-   **Request URL:**

```txt
GET: http://127.0.0.1:8000/api/vacation-plan/1/generate-pdf
```

-   **Response:**
    The response will be a PDF file download.

#### Example 2: Generate PDF for a Specific Holiday Plan (Not Found)

-   **Status:** 404 Not Found
-   **Request URL:**

```txt
GET: http://127.0.0.1:8000/api/vacation-plan/0/generate-pdf
```

-   **Response:**

```json
{
    "success": false,
    "message": "Vacation plan not found."
}
```
