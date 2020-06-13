# Let's define some constants for global configuration
ns_theme_newage {
    website {        
        paths {
            #cat = ns_theme_newage/website/settings/01; type=string; label=Template Path
            templateRootPath = typo3conf/ext/ns_theme_newage/Resources/Private/Templates/

            #cat = ns_theme_newage/website/settings/02; type=string; label=Layouts Path
            layoutRootPath = typo3conf/ext/ns_theme_newage/Resources/Private/Layouts/

            #cat = ns_theme_newage/website/settings/03; type=string; label=Partials Path
            partialRootPath = typo3conf/ext/ns_theme_newage/Resources/Private/Partials/
        }
    }
}
