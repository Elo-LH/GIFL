# GIFL 🫰💜

### PHP - Twig - JavaScript Vanilla - Sass - CSS - HTML

GIFL is an innovative project aimed at creating a user-friendly web platform for sharing, organizing, and discovering animated GIFs. It offers two main functionalities: a private space for registered users to upload, create, and modify GIF collections, and a public section where visitors can search and explore public collections available in the database.

This project was developed as part of the validation for the professional title of [web developer](https://www.francecompetences.fr/recherche/rncp/37674/). It represents a synthesis of skills acquired in web development, database management, and user interface design.

## Online Version

An online version of the site is available at: [GIFL](https://eloise-lh.go.yj.fr/index.php).

## 💻 Local setup

### 🗃️ Prerequisites

- [Node.js](https://nodejs.org/en/download/package-manager)
- [Composer](https://getcomposer.org/download/)
- [Wamp](https://wampserver.aviatechno.net/) (for Windows)
- [Git](https://git-scm.com/downloads)

### Cloning the GitHub repository:

![image](https://github.com/user-attachments/assets/fa1049f6-6647-4ce8-853a-600d2d8eb47a)

In a git bash terminal, navigate where you want to install your project (with `cd`, `mkdir`), then use command :

```bash
git clone https://github.com/Elo-LH/GIFL.git
```

### Wamp setup

Launch Wamp, left click to open menu and `select VirtualHosts` and then `manage VirtualHosts`.
![image](https://github.com/user-attachments/assets/01b3ebff-b3db-4d1f-93c9-828e7acefe99)

Create a new Virtual Host named "GIFL" with the complete file path of the repository
![image](https://github.com/user-attachments/assets/6002782e-35bf-4d71-b235-b1ae3ab63c3f)

You will need to restart DNS to ensure correct activation of the new Virtual Host.

### Database setup

#### Cloning the GIFL initial DB

From Wamp PhpMyAdmin, you can create an new DB and import `gifl_init_db.sql`

You will have the empty structure containing only one necessary user (GIFL public domain). In case of user deletion, their uploaded GIFs will be reassigned to GIFL public domain.

Password for this account is by default : `jd0!VG?gFwz9Cz.ZQh8:HsHK)`

#### Configuring Environment Variables

Create a `.env` file in the project root and configure it with your database information following the `.env.example`

Replace DB_USER, DB_PASSWORD, and DB_NAME with your own database credentials.

### Installing PHP Dependencies

From your git bash terminal, in your GIFL folder, install PHP dependencies using Composer:

```bash
composer install
```

To update dependencies use :

```bash
composer update
```

To generate autoload

```bash
composer dump-autoload
```

### Installing JavaScript Dependencies

Navigate to the project directory and install JavaScript dependencies using npm:

```bash
npm install
```

## 🏃 Local project run

To run your project, launch it from your Virtual Hosts list on Wamp

### Update autoload

To update autoload

```bash
composer dump-autoload
```

### Using Sass

To keep sass watching changes and updating css automatically :

```bash
sass -w assets/styles/sass:assets/styles/css
```

## License

This project is licensed under the Apache 2.0 License.
