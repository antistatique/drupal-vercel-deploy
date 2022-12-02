# Vercel Deploy

A Drupal 8 powered module.

|       Test-CI        |        Style-CI         |        Downloads        |         Releases         |
|:----------------------:|:-----------------------:|:-----------------------:|:------------------------:|
| [![Build Status](https://github.com/antistatique/drupal-vercel-deploy/actions/workflows/ci.yml/badge.svg)](https://github.com/antistatique/drupal-vercel-deploy/actions/workflows/ci.yml) | [![Code styles](https://github.com/antistatique/drupal-vercel-deploy/actions/workflows/styles.yml/badge.svg)](https://github.com/antistatique/drupal-vercel-deploy/actions/workflows/styles.yml) | [![Downloads](https://img.shields.io/badge/downloads-8.x--1.0-green.svg?style=flat-square)](https://ftp.drupal.org/files/projects/vercel_deploy-8.x-1.0.tar.gz) | [![Latest Stable Version](https://img.shields.io/badge/release-v1.0-blue.svg?style=flat-square)](https://www.drupal.org/project/vercel_deploy/releases) |

This module allows you to integrate Vercel deployments with Drupal.

## Usage of Vercel Deploy

  - Forced deployments from the Drupal User Interface.
  - (soon) Automatic deployments by hooking into content changes in Headless
  projects.
  - (soon) Scheduled deployments by configuring third-party cron job services to
  trigger the Drush Deploy Hook.
  - (soon) Forced deployments from the command line Drush.

One of the most used feature is the nice and big "Vercel Deploy" admin toolbar
button.

Users with the proper permissions (`vercel deploy button` and
`vercel deploy access`) will see an Admin Toolbar button, clicking this button
simply fires the Vercel Deploy process for the configured URLs.

## Versions

This module works on both Drupal 8, Drupal 9 & Drupal 10 !

## Dependencies

The Drupal 8, Drupal 9 & Drupal 10 version of this module requires nothing !
Feel free to use it.

## Supporting organizations

This project is sponsored by [Antistatique](https://www.antistatique.net), a Swiss Web Agency.
Visit us at [www.antistatique.net](https://www.antistatique.net) or
[Contact us](mailto:info@antistatique.net).

## Getting Started

We highly recommend you to install the module using `composer`.

<h2>Getting Started</h2>


```bash
$ composer require drupal/vercel_deploy
```

You can also install it using the `drush` or `drupal console` cli.

```bash
$ drush dl vercel_deploy
```

```bash
$ drupal module:install vercel_deploy
 ```
