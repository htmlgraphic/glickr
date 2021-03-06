# Glickr - AJAX Photo Gallery

---

## Setup
    pip install fabric (install homebrew)
    vagrant up
    fab tempdev get_database
    fab dev bootstrap

## Basic Vagrant commands
Use vagrant to manage the server.
All commands at `vagrant -h` 
Basic Commands include:

*   `vagrant up`: start server
*   `vagrant reload`: reload server
*   `vagrant suspend`: suspend server
*   `vagrant destroy`: destroy VM
*   `vagrant ssh`: shell into server

## Deployment
You can use 'production' instead of 'dev'

*   `fab dev bootstrap`: Install dependencies, deploy initial app to dev.
*   `fab dev deploy`: Deploy latest git commit to dev server.
