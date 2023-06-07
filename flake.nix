{
  description = "Tool zum Einreichen von Komplexprüfungen";

  outputs = { self, nixpkgs }:
    let
      supportedSystems = [ "x86_64-linux" "x86_64-darwin" "aarch64-linux" "aarch64-darwin" ];
      forAllSystems = nixpkgs.lib.genAttrs supportedSystems;
      pkgs = forAllSystems (system: nixpkgs.legacyPackages.${system});
    in
    {

      overlays.default = (_final: prev: {
        inherit (self.packages.${prev.system}) kpp;
      });

      nixosModules.default = {
        imports = [ ./module.nix ];

        nixpkgs.overlays = [ self.overlays.default ];
      };

      packages = forAllSystems (system: rec {

        default = kpp;

        kpp = pkgs.${system}.stdenvNoCC.mkDerivation {
          name = "kpp";
          src = ./.;
          phases = [ "unpackPhase" "installPhase" ];
          installPhase = ''
            mkdir -p $out
            cp -r $src/data $out
            cp -r $src/js $out
            cp -r $src/lang $out
            cp -r $src/php $out
            cp $src/index.php $out
            cp $src/style.css $out
            cp $src/pico.min.css $out
          '';
        };
      });
    };
}
