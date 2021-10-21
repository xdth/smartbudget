<h3 align="center">
  smartbudget
</h3>

<p align="center">
  <a href="https://dthlabs.com">
    <img alt="Made by dthlabs" src="https://img.shields.io/badge/made%20by-dthlabs-%2304D361">
  </a>

  <img alt="License" src="https://img.shields.io/badge/license-MIT-%2304D361">
</p>


## What is the smartbudget app?

**smartbudget** is an open-source application aiming to help you better organize your family finances. You can log your revenues and expenses, classify them by categories and compare to a previously defined budget, with the help of an amazing dashboard.


## Features

- User authentication: required in order to protect sensitive data;
- User management: add, edit or remove users, set them as admin if needed;
- Dashboard: visualize your data in a smart way;
- Budget: your typical monthly expenses list;
- Log: register your revenue and expenses;
- Categories: add, edit or remove categories;
- Items: add, edit or remove items (or sub-categories).


## Docker images

This app has two images, the latest app version and the demo, the latter containing dummy data for demonstration purposes.

To run the images:

- latest version: **`sudo docker run --name smartbudget -it -p 8000:8000 dthlabs/smartbudget`**

- app demo: **`sudo docker run --name smartbudget_demo -it -p 8000:8000 dthlabs/smartbudget_demo`**

Then visit http://localhost:8000 on your browser.

Enter the default login "admin" and its password "admin" (don't forget to change the admin password as soon as possible).


## Screenshots

 <table style="width:100%; border: none;">
  <tr style="border: none;">
    <td style="border: none;">
      <img src="https://i.imgur.com/Kjg14nI.png" alt="screenshot">
    </td>
  </tr>
  <tr style="border: none;">
    <td style="border: none;">
      <img src="https://i.imgur.com/YfdBMAA.png" alt="screenshot">
    </td>
  </tr>
  <tr style="border: none;">
    <td style="border: none;">
      <img src="https://i.imgur.com/Qk4SaNQ.png" alt="screenshot">
    </td>
  </tr>
  <tr style="border: none;">
    <td style="border: none;">
      <img src="https://i.imgur.com/VLgcJO7.png" alt="screenshot">
    </td>
  </tr>
</table>