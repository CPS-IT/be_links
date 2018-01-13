# TYPO3 Extension be_links

[![Latest Stable Version](https://img.shields.io/packagist/v/cpsit/be-links.svg)](https://packagist.org/packages/cpsit/be-links)
[![Build Status](https://img.shields.io/travis/CPS-IT/be_links/master.svg)](https://travis-ci.org/CPS-IT/be_links)
[![StyleCI](https://styleci.io/repos/22637917/shield?branch=master)](https://styleci.io/repos/22637917)

Add page or web links as backend modules

## Installation

Simply install the extension with Composer or the Extension Manager.

## Usage

1. Add a new page of type folder
2. Create a new record of type `Backend link` 
3. Enter a title
    - The title is used as module title
4. Choose between main or sub module
5. Enter a url
    - The url is the site shown in your module
6. Choose an icon
    - The icon is used for your module
    - If no icon is provided, a default icon is used
7. Set your preferred authentication type
    - `None`: The module is accessible for all users
    - `User & Group`: The module permission can be set in group and user records
    - `Admin only`: The module is accessible for admins only
    - `User`: The module permission can be set in user records
    - `Group`: The module permission can be set in group records
8. Choose the parent module if you chose the sub module type
