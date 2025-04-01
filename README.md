# Real Estate Website

A modern and responsive real estate website built with PHP, MySQL, and Bootstrap.

## Features

- User authentication (login/register)
- Property listing and search
- Property details view
- Contact form for property inquiries
- Modern and responsive design
- User dashboard for property management

## Setup Instructions

1. Clone this repository to your local machine
2. Set up a local web server (e.g., XAMPP, WAMP)
3. Import the database schema from `database.sql`
4. Configure database connection in `config/database.php`
5. Add required images to the `images` directory

## Required Images

The following images are required for the website to function properly:

### Background Images
- `images/hero-bg.jpg` - A high-quality real estate background image for the hero section
  - Recommended size: 1920x1080px
  - Format: JPG
  - File size: Optimize to under 500KB

### Property Images
- `images/default-property.jpg` - Default property image used when no specific image is provided
  - Recommended size: 800x600px
  - Format: JPG
  - File size: Optimize to under 200KB

## Image Guidelines

1. **Hero Background Image (`hero-bg.jpg`)**
   - Use a high-quality real estate image
   - Should be light enough to overlay text
   - Avoid busy patterns
   - Recommended: Modern house exterior or luxury real estate

2. **Default Property Image (`default-property.jpg`)**
   - Use a neutral, professional property image
   - Should work well as a placeholder
   - Recommended: Generic house or apartment image

## Image Sources

You can find free, high-quality images from these sources:
1. Unsplash (https://unsplash.com/s/photos/real-estate)
2. Pexels (https://www.pexels.com/search/real%20estate/)
3. Pixabay (https://pixabay.com/images/search/real%20estate/)

## Directory Structure

```
├── config/
│   └── database.php
├── css/
│   └── style.css
├── images/
│   ├── hero-bg.jpg
│   └── default-property.jpg
├── includes/
│   └── header.php
├── index.php
├── login.php
├── register.php
├── property_details.php
└── database.sql
```

## Technologies Used

- PHP 7.4+
- MySQL 5.7+
- Bootstrap 5.1.3
- Bootstrap Icons 1.7.2
- Google Fonts (Poppins)

## Contributing

1. Fork the repository
2. Create your feature branch
3. Commit your changes
4. Push to the branch
5. Create a new Pull Request

## License

This project is licensed under the MIT License - see the LICENSE file for details. 