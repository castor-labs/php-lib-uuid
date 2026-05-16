{ pkgs, ... }:

{
  env = {
    LANG = "C.UTF-8";
    LC_ALL = "C.UTF-8";
    LC_CTYPE = "C.UTF-8";
    LC_COLLATE = "C.UTF-8";
  };

  languages.php = {
    enable = true;
    version = "8.3";

    extensions = [
      "bcmath"
      "xdebug"
    ];

    ini = ''
      memory_limit = 512M
      max_execution_time = 60
      xdebug.mode = debug,develop,coverage
    '';
  };

  packages = [
    pkgs.git
    pkgs.unzip
  ];

  scripts.test.exec = ''
    composer test
  '';

  scripts.analyse.exec = ''
    composer mago:analyze
  '';

  scripts.serve-coverage.exec = ''
    if [ ! -f .dev/coverage/index.html ]; then
      composer test
    fi

    php -S 127.0.0.1:8080 -t .dev/coverage
  '';
}
