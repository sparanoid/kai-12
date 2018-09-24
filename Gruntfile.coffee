"use strict"
module.exports = (grunt) ->

  # Load Sass deps
  sass = require('node-sass')

  # Load all grunt tasks
  require("matchdep").filterDev("grunt-*").forEach grunt.loadNpmTasks

  # Configurable paths
  coreConfig =
    app: "."
    assets: "."
    dist: "."
    pkg: grunt.file.readJSON("package.json")
    banner: "/* <%= core.pkg.name %> v<%= core.pkg.version %> | © <%= core.pkg.author %> | <%= core.pkg.license %> */"

  # Project configurations
  grunt.initConfig
    core: coreConfig

    coffeelint:
      options:
        indentation: 2
        no_stand_alone_at:
          level: "error"
        no_empty_param_list:
          level: "error"
        max_line_length:
          level: "ignore"

      test:
        files:
          src: ["Gruntfile.coffee"]

    watch:
      coffee:
        files: ["<%= coffeelint.test.files.src %>"]
        tasks: ["coffeelint"]

      less:
        files: ["<%= core.app %>/assets/less/**/*.less"]
        tasks: ["less:server", "autoprefixer"]

      sass:
        files: ["<%= core.app %>/assets/scss/**/*.scss"]
        tasks: ["sass:server", "autoprefixer"]

    less:
      server:
        options:
          strictMath: true
          dumpLineNumbers: "comments"

        src: ["<%= core.app %>/assets/less/app.less"]
        dest: "<%= core.dist %>/app.css"

      dist:
        src: ["<%= less.server.src %>"]
        dest: "<%= less.server.dest %>"

    sass:
      options:
        implementation: sass
        precision: 10

      serve:
        options:
          outputStyle: "nested"
          sourceMapContents: true
          sourceMapEmbed: true

        files: [
          expand: true
          cwd: "<%= core.app %>/assets/scss/"
          src: ["**/app*.scss"]
          dest: "<%= core.dist %>/"
          ext: ".css"
        ]

      dist:
        options:
          outputStyle: "compressed"

        files: "<%= sass.serve.files %>"

    autoprefixer:
      dist:
        src: ["<%= less.server.dest %>"]
        dest: "<%= less.server.dest %>"

    cssmin:
      dist:
        options:
          report: "gzip"

        files: [
          expand: true
          cwd: "<%= core.dist %>"
          src: ["app*.css", "!*.min.css"]
          dest: "<%= core.dist %>/"
        ]

    compress:
      dist:
        options:
          archive: ".tmp/<%= core.pkg.name %>.zip"

        files: [
          expand: true
          cwd: "<%= core.app %>/"
          src: ["**", "!node_modules/**", "!templates/**", "!assets/**", "!*.coffee", "!*.json", "!*.scss", "!*.less"]
          dest: "<%= core.pkg.name %>"
        ]

    copy:
      prepare:
        files: [
          src: ".tmp/<%= core.pkg.name %>.zip"
          dest: ".tmp/<%= core.pkg.name %>-<%= core.pkg.version %>.zip"
        ]

      dist:
        files: [
          expand: true
          cwd: ".tmp/"
          src: ["<%= core.pkg.name %>*.zip"]
          dest: "/Users/sparanoid/Git/sparanoid.com-prod/rsrc/download/"
        ,
          expand: true
          cwd: "templates/"
          src: ["<%= core.pkg.name %>.html"]
          dest: "/Users/sparanoid/Git/sparanoid.com-prod/site/lab/wordpress/"
        ]

    replace:
      dist:
        options:
          variables:
            "version": "<%= core.pkg.version %>"
            "package": "<%= core.pkg.name %>"
          prefix: "@@"

        files: [
          expand: true
          flatten: true
          cwd: "<%= core.app %>/"
          src: ["templates/<%= core.pkg.name %>.json"]
          dest: "/Users/sparanoid/Git/sparanoid.com-prod/site/lab/wordpress/"
        ]

    clean: [".tmp"]

  grunt.registerTask "serve", ["clean", "test", "sass:server", "autoprefixer", "watch"]
  grunt.registerTask "test", ["coffeelint"]
  grunt.registerTask "build", ["clean", "test", "sass:dist", "autoprefixer", "cssmin"]
  grunt.registerTask "deploy", ["build", "compress", "copy", "replace", "clean"]
  grunt.registerTask "default", ["build"]
