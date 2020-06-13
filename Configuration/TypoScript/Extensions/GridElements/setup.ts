# TypoScript for rendering in frontend
tt_content.gridelements_pi1.20.10.setup {
    nsBase1Col < lib.gridelements.defaultGridSetup
    nsBase1Col {
        cObject = FLUIDTEMPLATE
        cObject {
            file = typo3conf/ext/ns_theme_newage/Resources/Private/Extensions/GridElements/nsBase1col.html
        }
    }
    nsBase2Col < lib.gridelements.defaultGridSetup
    nsBase2Col {
        cObject = FLUIDTEMPLATE
        cObject {
            file = typo3conf/ext/ns_theme_newage/Resources/Private/Extensions/GridElements/nsBase2col.html
        }
    }
}