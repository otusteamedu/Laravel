@extends('layouts.inner')

@section('title')
    Профиль
@endsection

@section('head')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4Q6Gf2aSP4eDXB8Miphtr37CMZZQ5oXLH2yaXMJ2w8e2ZtHTl7GptT4jmndRuHDT" crossorigin="anonymous">
    @parent
    <style>
        a{
            text-decoration: none;
        }

        footer a,
        header a{
            color: #fff;
        }

        footer ul,
        header ul{
            margin: 0;
            padding: 0;
        }

        header h1{
            font-size: 1.25em
        }

        .menu-top a{
           padding: 0 !important;
        }

        .profile-card {
            background: #ffffff;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1); /* Subtle shadow */
            padding: 20px;
            margin-bottom: 20px;
        }
        .profile-card h2 {
            color: #2c3e50; /* Darker heading */
            margin-bottom: 20px;
        }
        .profile-avatar {
            width: 150px; /* Increased size */
            height: 150px;
            border-radius: 50%;
            object-fit: cover; /* Ensure image covers the area */
            margin-bottom: 20px;
            border: 5px solid #ffffff; /* White border */
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        .profile-info p {
            margin-bottom: 10px;
            color: #555; /* Medium gray text */
        }
        .profile-info i {
            margin-right: 10px;
            color: #3498db; /* Blue icon color */
        }
        .edit-profile-btn {
            margin-top: 20px;
        }
        .nav-pills .nav-link.active {
            background-color: #3498db;  /* Active tab background color */
            color: white;
        }

        .nav-pills .nav-link {
            color: #555; /* Normal tab text color */
            border-radius: 10px;
            margin-bottom: 5px; /* Add some space between tabs */
        }

        .nav-pills .nav-link:hover {
            background-color: #e0e0e0; /* Hover background color */
            color: #3498db;  /* Hover text color */
        }

        @media (max-width: 767px) {
            .menu-top a{
                padding: 20px !important;
            }
        }

    </style>
@endsection


@section('inner-content')
    <div class="profile-card">
        <div class="text-center">
            <h2>John Doe</h2>
        </div>
        <div class="profile-info">
            <p><i class="fas fa-envelope"></i> Email: <span id="email">john.doe@example.com</span></p>
            <p><i class="fas fa-phone"></i> Phone: <span id="phone">(123) 456-7890</span></p>
            <p><i class="fas fa-map-marker-alt"></i> Location: <span id="location">New York, NY</span></p>
            <p><i class="fas fa-birthday-cake"></i> Birthday: <span id="birthday">January 1, 1990</span></p>
        </div>
        <div class="text-center">
            <button class="btn btn-primary edit-profile-btn" id="edit-button">Edit Profile</button>
        </div>
    </div>
    <div class="profile-card">
        <ul class="nav nav-pills mb-3 justify-content-center" id="pills-tab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="pills-posts-tab" data-bs-toggle="pill" data-bs-target="#pills-posts" type="button" role="tab" aria-controls="pills-posts" aria-selected="true">Posts</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="pills-albums-tab" data-bs-toggle="pill" data-bs-target="#pills-albums" type="button" role="tab" aria-controls="pills-albums" aria-selected="false">Albums</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="pills-settings-tab" data-bs-toggle="pill" data-bs-target="#pills-settings" type="button" role="tab" aria-controls="pills-settings" aria-selected="false">Settings</button>
            </li>
        </ul>

        <div class="tab-content" id="pills-tabContent">
            <div class="tab-pane fade show active" id="pills-posts" role="tabpanel" aria-labelledby="pills-posts-tab">
                <h4>User Posts</h4>
                <p>This is where the user's posts would be displayed.</p>
                <ul id="posts-list">
                    <li>Post 1</li>
                    <li>Post 2</li>
                    <li>Post 3</li>
                </ul>
            </div>
            <div class="tab-pane fade" id="pills-albums" role="tabpanel" aria-labelledby="pills-albums-tab">
                <h4>User Albums</h4>
                <p>This is where the user's photo albums would be displayed.</p>
                <ul id="albums-list">
                    <li>Album 1</li>
                    <li>Album 2</li>
                    <li>Album 3</li>
                </ul>
            </div>
            <div class="tab-pane fade" id="pills-settings" role="tabpanel" aria-labelledby="pills-settings-tab">
                <h4>User Settings</h4>
                <p>This is where the user can manage their profile settings.</p>
                <form id="settings-form">
                    <div class="mb-3">
                        <label for="name" class="form-label">Name</label>
                        <input type="text" class="form-control" id="name" value="John Doe">
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" value="john.doe@example.com">
                    </div>
                    <button type="submit" class="btn btn-primary">Update Settings</button>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('body-bottom')
    @include('blocks.form')
    @parent

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js" integrity="sha384-j1CDi7MgGQ12Z7Qab0qlWQ/Qqz24Gc6BM0thvEMVjHnfYGF0rmFCozFSxQBxwHKO" crossorigin="anonymous"></script>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const editButton = document.getElementById('edit-button');
            const profileInfo = document.querySelector('.profile-info');
            const nameElement = document.getElementById('name');
            const emailElement = document.getElementById('email');
            const phoneElement = document.getElementById('phone');
            const locationElement = document.getElementById('location');
            const birthdayElement = document.getElementById('birthday');


            let isEditing = false;

            editButton.addEventListener('click', () => {
                if (isEditing) {
                    // Save changes
                    const newName = nameElement.textContent;
                    const newEmail = emailElement.textContent;
                    const newPhone = phoneElement.textContent;
                    const newLocation = locationElement.textContent;
                    const newBirthday = birthdayElement.textContent;


                    // Update profile info (in real scenario, send to server)
                    // For demonstration, we'll just keep the edited values
                    nameElement.textContent = newName;
                    emailElement.textContent = newEmail;
                    phoneElement.textContent = newPhone;
                    locationElement.textContent = newLocation;
                    birthdayElement.textContent = newBirthday;


                    editButton.textContent = 'Edit Profile';
                    isEditing = false;
                } else {
                    // Enable editing
                    nameElement.contentEditable = 'true';
                    emailElement.contentEditable = 'true';
                    phoneElement.contentEditable = 'true';
                    locationElement.contentEditable = 'true';
                    birthdayElement.contentEditable = 'true';


                    nameElement.focus();
                    editButton.textContent = 'Save Changes';
                    isEditing = true;
                }
            });
        });
    </script>
@endsection
