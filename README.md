# SnowTricks - PHP/Symfony Project
Projet 6 Open Classrooms

This repository contains the code for the SnowTricks website, developed as part of the "Développeur d'application - PHP/Symfony" program. The website aims to create a collaborative platform for learning and sharing snowboarding tricks, where users can contribute content, discuss techniques, and interact with others.

Project Overview
The project involves the creation of a community-driven platform for snowboarding tricks, where users can:
 - View and explore a list of snowboarding tricks.
 - Create, modify, and view detailed pages for each trick.
 - Participate in discussions related to specific tricks.

Features
1. Homepage - Snowboarding Trick List
Publicly accessible page listing snowboarding tricks.

Each trick's name is clickable, leading to its detailed page.

If logged in, users can edit or delete tricks via icons next to each trick.

2. Create a Snowboarding Trick
Form to submit new tricks, including:

Trick name

Description

Trick group

Illustrations (image(s))

Videos (embed code from platforms like YouTube, Dailymotion or others)

Form accessible only to authenticated users.

Redirects to the trick list with a success or error message upon submission.

3. Edit a Snowboarding Trick
Same fields as for creation, with pre-filled data for editing existing tricks.

4. Trick Detail Page
Displays trick name, description, group, images, videos, and discussion space.

URL structure includes the trick name as a slug.

Discussion section where users can comment on each trick.

The discussion section is visible for everyone, but only authenticated users can post messages.

5. Discussion Space
Publicly visible to all users, but only authenticated users can post messages.

Each message shows the author's name, photo, date, and content.

Pagination for messages (10 per page).

6. Authentication
Login Page: Users can log in using their username and password.

Registration Page: Users can register by providing a username, email, and password. Email verification with access token is required to activate the account.

Forgot Password: Users can reset their password via email with access token after providing their username.

Password Reset Page: Users can set a new password after following the reset link.

Installation
To run this project locally:

Clone the repository:

bash
Copy
Edit
git clone https://github.com/yourusername/snowtricks.git
Navigate to the project directory:

bash
Copy
Edit
cd snowtricks
Install dependencies:

bash
Copy
Edit
composer install
Set up the database:

bash
Copy
Edit
php bin/console doctrine:database:create
php bin/console doctrine:schema:update --force
Access the application:

Run the Symfony server:

bash
Copy
Edit
symfony serve
Open your browser and go to http://localhost:8000.

License
This project is licensed under the MIT License - see the LICENSE file for details.
