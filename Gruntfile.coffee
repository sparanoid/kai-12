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

      sass:
        files: ["<%= core.app %>/assets/scss/**/*.scss"]
        tasks: ["sass:serve"]

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
          outputStyle: "nested"

        files: "<%= sass.serve.files %>"

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
      sparanoid:
        options:
          archive: ".tmp/<%= core.pkg.name %>.zip"

        files: [
          expand: true
          cwd: "<%= core.app %>/"
          src: ["**", "!builds/**", "!node_modules/**", "!templates/**", "!assets/**", "!*.coffee", "!*.json", "!*.scss"]
          dest: "<%= core.pkg.name %>"
        ]

      wporg:
        options:
          archive: ".tmp/<%= core.pkg.name %>-wporg.zip"

        files: [
          expand: true
          cwd: "<%= core.app %>/"
          src: ["**", "!**/theme-update-checker.php", "!builds/**", "!node_modules/**", "!templates/**", "!assets/**", "!*.coffee", "!*.json", "!*.scss"]
          dest: "<%= core.pkg.name %>"
        ]

    copy:
      sparanoid_prepare:
        files: [
          src: ".tmp/<%= core.pkg.name %>.zip"
          dest: ".tmp/<%= core.pkg.name %>-<%= core.pkg.version %>.zip"
        ]

      sparanoid:
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

      wporg_prepare:
        files: [
          src: ".tmp/<%= core.pkg.name %>-wporg.zip"
          dest: ".tmp/<%= core.pkg.name %>-wporg-<%= core.pkg.version %>.zip"
        ]

      wporg:
        files: [
          expand: true
          cwd: ".tmp/"
          src: ["<%= core.pkg.name %>*.zip"]
          dest: "builds/"
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

  grunt.registerTask "serve", ["clean", "test", "sass:serve", "watch"]
  grunt.registerTask "test", ["coffeelint"]
  grunt.registerTask "build", ["clean", "test", "sass:dist"]
  grunt.registerTask "deploy", ["build", "compress:sparanoid", "copy:sparanoid_prepare", "copy:sparanoid", "replace", "clean"]
  grunt.registerTask "deploy_wporg", ["build", "compress:wporg", "copy:wporg_prepare", "copy:wporg", "clean"]
  grunt.registerTask "default", ["build"]
