# Galleros API Documentation

This document describes the available API endpoints for the Galleros application. All endpoints return JSON responses.

---

## Authentication

### Login

- **Endpoint:** `/public/api/auth.php`
- **Method:** `POST`
- **Request Body:**
  ```json
  {
    "name": "username",
    "password": "userpassword"
  }
  ```
- **Response:**
  ```json
  {
    "success": true,
    "user": { "id": 1, "name": "username" }
  }
  ```

### Logout

- **Endpoint:** `/public/api/logout.php`
- **Method:** `GET`
- **Response:**
  ```json
  { "success": true }
  ```

---

## Users

### Get All Users

- **Endpoint:** `/public/api/user.php`
- **Method:** `GET`
- **Response:**
  ```json
  [
    { "id": 1, "name": "alice" },
    { "id": 2, "name": "bob" }
  ]
  ```

### Get User by ID

- **Endpoint:** `/public/api/user.php?id=1`
- **Method:** `GET`
- **Response:**
  ```json
  { "id": 1, "name": "alice" }
  ```

### Register User

- **Endpoint:** `/public/api/user.php`
- **Method:** `POST`
- **Request Body:**
  ```json
  {
    "name": "newuser",
    "password": "newpassword"
  }
  ```
- **Response:**
  ```json
  { "id": 3, "name": "newuser" }
  ```

---

## Galleries

### Get All Galleries

- **Endpoint:** `/public/api/gallery.php`
- **Method:** `GET`
- **Response:**
  ```json
  [
    { "id": 1, "name": "Nature", "author": { "id": 1, "name": "alice" } }
  ]
  ```

### Get Gallery by ID

- **Endpoint:** `/public/api/gallery.php?id=1`
- **Method:** `GET`
- **Response:**
  ```json
  { "id": 1, "name": "Nature", "author": { "id": 1, "name": "alice" } }
  ```

### Create Gallery

- **Endpoint:** `/public/api/gallery.php`
- **Method:** `POST`
- **Request Body:**
  ```json
  {
    "name": "New Gallery",
    "user_id": 1
  }
  ```
- **Response:**
  ```json
  { "id": 2, "name": "New Gallery", "author": { "id": 1, "name": "alice" } }
  ```

---

## Posts

### Get All Posts

- **Endpoint:** `/public/api/post.php`
- **Method:** `GET`
- **Response:**
  ```json
  [
    { "id": 1, "title": "Sunset", "gallery": { ... }, ... }
  ]
  ```

### Get Post by ID

- **Endpoint:** `/public/api/post.php?id=1`
- **Method:** `GET`
- **Response:**
  ```json
  { "id": 1, "title": "Sunset", "gallery": { ... }, ... }
  ```

### Get Posts by Gallery

- **Endpoint:** `/public/api/post.php?gallery=1`
- **Method:** `GET`
- **Response:**
  ```json
  [
    { "id": 1, "title": "Sunset", ... }
  ]
  ```

### Create Post

- **Endpoint:** `/public/api/post.php`
- **Method:** `POST`
- **Request Body:**
  ```json
  {
    "title": "New Post",
    "gallery_id": 1
  }
  ```
- **Response:**
  ```json
  { "id": 2, "title": "New Post", ... }
  ```

---

## Comments

### Get Comments for a Post

- **Endpoint:** `/public/api/comment.php?post=1`
- **Method:** `GET`
- **Response:**
  ```json
  [
    { "id": 1, "content": "Nice!", "author": { ... }, ... }
  ]
  ```

### Create Comment

- **Endpoint:** `/public/api/comment.php`
- **Method:** `POST`
- **Request Body:**
  ```json
  {
    "post_id": 1,
    "content": "Great photo!"
  }
  ```
- **Response:**
  ```json
  { "id": 2, "content": "Great photo!", ... }
  ```

---

## File Upload

### Upload Gallery or Post Image

- **Endpoint:** `/public/api/upload.php`
- **Method:** `POST` (multipart/form-data)
- **Fields:**
  - `title`: string
  - `type`: "gallery" or "post"
  - `thumbnail`: image file
  - `gallery_id`: (for posts) integer

- **Response:**
  ```json
  {
    "success": true,
    "id": 3,
    "title": "My Gallery",
    "thumbnail_path": "img/galleries/3/thumbnail.jpg"
  }
  ```

---

## Error Responses

All endpoints return an `error` field in the response if something goes wrong:

```json
{ "error": "Description of the error" }
```

---

## Notes

- All requests and responses use JSON unless otherwise specified.
- Some endpoints require authentication (login).
- For file uploads, use `multipart/form-data`.

