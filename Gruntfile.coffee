"use strict"
module.exports = (grunt) ->

  # Load all grunt tasks
  require("matchdep").filterDev("grunt-*").forEach grunt.loadNpmTasks

  # Configurable paths
  coreConfig =
    app: "."
    assets: "."
    dist: "."
    pkg: grunt.file.readJSON("package.json")
    banner: do ->
      banner = "/*\n"
      banner += " * (c) <%= core.pkg.author %>.\n *\n"
      banner += " * <%= core.pkg.name %> - v<%= core.pkg.version %> (<%= grunt.template.today('mm-dd-yyyy') %>)\n"
      banner += " * <%= core.pkg.homepage %>\n"
      banner += " * <%= core.pkg.license.type %> - <%= core.pkg.license.url %>\n"
      banner += " */"
      banner

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

    csslint:
      options:
        csslintrc: "<%= core.app %>/assets/less/.csslintrc"

      test:
        src: ["<%= core.app %>/app.css"]

    phplint:
      test:
        files:
          src: ["<%= core.app %>/{,*/}*.php"]

    watch:
      coffee:
        files: ["<%= coffeelint.test.files.src %>"]
        tasks: ["coffeelint"]

      phplint:
        files: ["<%= phplint.test.files.src %>"]
        tasks: ["phplint"]

      less:
        files: ["<%= core.app %>/assets/less/**/*.less"]
        tasks: ["less:server", "autoprefixer", "csslint"]

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

    autoprefixer:
      dist:
        src: ["<%= less.server.dest %>"]
        dest: "<%= less.server.dest %>"

    csscomb:
      options:
        config: "<%= core.app %>/assets/less/.csscomb.json"

      dist:
        src: ["<%= less.server.dest %>"]
        dest: "<%= less.server.dest %>"

    cssmin:
      dist:
        options:
          banner: "<%= core.banner %>"
          report: "gzip"

        files:
          "<%= core.dist %>/app.css": ["<%= core.dist %>/app.css"]

    compress:
      dist:
        options:
          archive: ".tmp/<%= core.pkg.name %>.zip"

        files: [
          expand: true
          cwd: "<%= core.app %>/"
          src: ["**", "!node_modules/**", "!templates/**", "!assets/**", "!*.coffee", "!*.json", "!*.less"]
          dest: "<%= core.pkg.name %>"
        ]

    copy:
      dist:
        files: [
          expand: true
          cwd: ".tmp/"
          src: ["<%= core.pkg.name %>*.zip"]
          dest: "/Users/sparanoid/Dropbox/Sites/static.sparanoid.com/download/"
        ,
          expand: true
          cwd: "templates/"
          src: ["<%= core.pkg.name %>.html"]
          dest: "/Users/sparanoid/Dropbox/Sites/sparanoid.com/lab/wordpress/"
        ]

    # TODO: `cwd` doesn't work here.
    rename:
      dist:
        files: [
          src: [".tmp/<%= core.pkg.name %>.zip"]
          dest: ".tmp/<%= core.pkg.name %>-<%= core.pkg.version %>.zip"
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
          dest: "/Users/sparanoid/Dropbox/Sites/sparanoid.com/lab/wordpress/"
        ]

    clean: [".tmp"]

  grunt.registerTask "serve", ["clean", "test", "less:server", "autoprefixer", "watch"]
  grunt.registerTask "test", ["coffeelint", "csslint"]
  grunt.registerTask "build", ["clean", "test", "less:dist", "autoprefixer", "csscomb", "cssmin"]
  grunt.registerTask "deploy", ["clean", "test", "less:dist", "autoprefixer", "csscomb", "cssmin", "compress", "copy", "rename", "copy", "replace", "clean"]
  grunt.registerTask "default", ["build"]
