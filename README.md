# Courtyard Forum Component

[![Build Status](https://secure.travis-ci.org/Courtyard/Forum.png)](http://travis-ci.org/Courtyard/Forum)

**NOTE: This library is still under heavy development!** [Development Roadmap](https://trello.com/board/public-development-board/5069f110fdd2aceb13cf88c5).

## Courtyard

Courtyard is a forum platform designed specifically for developers.  After working on hundreds of forums, including some of the worlds busiest over the past decade, I've realized most forums are hacky content management systems littered with plugins.  They do not age well.  In order to build stable, scalable and extendable online communities, you need a much stronger foundation to build upon.  

The Courtyard suite will supply all of the building blocks for developers to build their own online community with exactly the tools and features they need.  More importantly, one that allows you to manage dependencies, version your code, and test all of the layers automatically.

## Courtyard\Forum

This is core package of the suite.  It provides an event-driven API for developers to tap into.  We're providing a ForumBundle for Symfony2 integration, but the component should be abstracted enough to use on its own.