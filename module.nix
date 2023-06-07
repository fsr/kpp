{ lib, pkgs, config, ... }:
with lib;
let
  cfg = config.services.kpp;
in
{
  options.services.kpp = {
    enable = mkEnableOption "kpp";
    hostName = mkOption {
      type = types.nullOr types.str;
      default = null;
      example = "kpp.example.com";
      description = ''
        The hostname the application should be served on.
        If it is `null`, nginx will not be automatically configured.
      '';
    };
    user = mkOption {
      type = types.str;
      default = "kpp";
      description = "The user under which the server runs.";
    };
    group = mkOption {
      type = types.str;
      default = "kpp";
      description = "The group under which the server runs.";
    };
    dataDir = mkOption {
      type = types.path;
      default = "/var/lib/kpp";
      description = lib.mdDoc ''
        Data directory
      '';
    };

  };

  config = lib.mkIf cfg.enable {
    services.phpfpm.pools.kpp = {
      user = cfg.user;
      group = cfg.group;
      settings = {
        "listen.owner" = config.services.nginx.user;
        "pm" = "dynamic";
        "pm.max_children" = 32;
        "pm.max_requests" = 500;
        "pm.start_servers" = 2;
        "pm.min_spare_servers" = 2;
        "pm.max_spare_servers" = 5;
        "php_admin_value[error_log]" = "stderr";
        "php_admin_flag[log_errors]" = true;
        "catch_workers_output" = true;
      };
      phpEnv."PATH" = lib.makeBinPath [ pkgs.php ];
    };

    users.users.kpp = lib.mkIf (cfg.user == "kpp") {
      group = cfg.group;
      isSystemUser = true;
    };
    users.groups.kpp = lib.mkIf (cfg.group == "kpp") { };
    systemd.tmpfiles.rules = [
      "d '${cfg.dataDir}' 0700 ${cfg.user} ${cfg.group} - -"
    ];

    services.nginx = lib.mkIf (cfg.hostName != null) {
      enable = true;

      virtualHosts.${cfg.hostName} = {
        root = pkgs.kpp;
        locations = {
          "= /" = {
            extraConfig = ''
              rewrite ^ /index.php;
            '';
          };
          "~ \.php$" = {
            extraConfig = ''
              try_files $uri =404;
              fastcgi_pass unix:${config.services.phpfpm.pools.kpp.socket};
              fastcgi_index index.php;
              include ${pkgs.nginx}/conf/fastcgi_params;
              include ${pkgs.nginx}/conf/fastcgi.conf;
            '';
          };
        };
      };
    };
  };
}
