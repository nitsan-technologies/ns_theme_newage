# Include the BackendLayouts
<INCLUDE_TYPOSCRIPT: source="DIR:EXT:ns_theme_newage/Configuration/PageTSconfig/BackendLayouts" extensions="ts">

// Include the BackendLayouts
<INCLUDE_TYPOSCRIPT: source="DIR:EXT:ns_theme_newage/Configuration/PageTSconfig/GridElements" extensions="ts">

# Remove default custom elements from EXT:ns_basetheme
TCEFORM {
    tt_content {
        layout {
            altLabels.1 = Feature heading block
            removeItems = 2,3
        }
    }
    pages{
    	layout{
    		altLabels.1 = Only current page content
            removeItems = 2,3
    	}
    }
}