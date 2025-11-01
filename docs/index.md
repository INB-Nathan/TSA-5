# Brewkaholic Documentation

Welcome to the documentation for the Brewkaholic application.

This document provides an overview of the application, its features, and how to use it.

## Table of Contents

- [Introduction](#introduction)
- [Features](#features)
- [Usage](#usage)
- [Database Schema](#database-schema)

## Introduction

Brewkaholic is a Technical Summative Assesment for Web System Development in FEU-TECH. It is a coffee shop page where customers can view available items. It also provides an admin interface with secure session management for managing the menu and other content.

## Features

- Customer view: Browse available coffee, pastries, and other items.
- Admin panel: Manage items, categories, and availability.
- User authentication: Separate access for customers and admins.
- Security Functions: Input Validation in the login form and registration forms.

## Usage

- Access the application through your web browser.
- Register as a customer to browse the menu.
- Log in as an admin to manage the application content.

## Database Schema

The database schema is defined in the `schema.sql` file in the root of the project. It includes tables for users, roles, items, categories, and more.

## Login

The application has a login page as the default entry point. Users can log in with their username and password.

The login form is located at `app/Views/login.php`.
The login logic is handled by the `Login` controller at `app/Controllers/Login.php`.

## Registration

Users can register for a new account by clicking the "Register here" link on the login page.
The registration form requires a username, email, password, and password confirmation.

The registration form is located at `app/Views/register.php`.
The registration logic is handled by the `Register` controller at `app/Controllers/Register.php`.

## Authentication

The application uses a filter to protect routes that require authentication.
The `AuthFilter` at `app/Filters/AuthFilter.php` checks if a user is logged in before allowing access to a protected page.
If the user is not logged in, they are redirected to the login page.
