
// Initiate Page Object
page = PAGE
page {
  // Setup favion  
  shortcutIcon = typo3conf/ext/ns_theme_newage/Resources/Public/img/favicon.ico

  // Set viewport
  meta {
    viewport = width=device-width,initial-scale=1, maximum-scale=1, user-scalable=no
  }

  // Initiate all the css-together
  includeCSS {
    20 =
    50 = typo3conf/ext/ns_theme_newage/Resources/Public/vendor/bootstrap/css/bootstrap.min.css
    80 = typo3conf/ext/ns_theme_newage/Resources/Public/vendor/fontawesome-free/css/all.min.css
    90 = typo3conf/ext/ns_theme_newage/Resources/Public/vendor/simple-line-icons/css/simple-line-icons.css        
    110 = https://fonts.googleapis.com/css?family=Catamaran:100,200,300,400,500,600,700,800,900"
    120 = https://fonts.googleapis.com/css?family=Kaushan+Script
    130 = https://fonts.googleapis.com/css?family=Muli
    140 = typo3conf/ext/ns_theme_newage/Resources/Public/css/new-age.css
    150 = typo3conf/ext/ns_theme_newage/Resources/Public/css/custom.css
  }

  // Initiate all the js-together
  includeJSFooter {
    10 = typo3conf/ext/ns_theme_newage/Resources/Public/vendor/jquery/jquery.min.js
    20 = typo3conf/ext/ns_theme_newage/Resources/Public/vendor/bootstrap/js/bootstrap.bundle.min.js        
    30 = typo3conf/ext/ns_theme_newage/Resources/Public/vendor/jquery-easing/jquery.easing.min.js        
    40 = typo3conf/ext/ns_theme_newage/Resources/Public/js/new-age.js
  }

  # <body> class based on backend_layout
  bodyTagCObject = COA
  bodyTagCObject {
    wrap = <body class="|" id="page-top">

    10 = COA
    10 {
      # backend layout
      50 = CASE
      50 {
        key {
          data = levelfield:-1, backend_layout_next_level, slide
          override.field = backend_layout
        }

        default = TEXT
        default.value = default

        pagets__content = TEXT
        pagets__content.value = content
      }
    }
  }

  10 = FLUIDTEMPLATE
  10 {
    layoutRootPath = {$ns_theme_newage.website.paths.layoutRootPath}
    partialRootPath = {$ns_theme_newage.website.paths.partialRootPath}
    templateRootPath = {$ns_theme_newage.website.paths.templateRootPath}

    // Let's automatically choose backend layout and template
    file.stdWrap.cObject = CASE
    file.stdWrap.cObject {
      key {
        data = levelfield:-1, backend_layout_next_level, slide
        override.field = backend_layout
      }

      default = TEXT
      default.value = {$ns_theme_newage.website.paths.templateRootPath}Default.html

      pagets__content = TEXT
      pagets__content.value = {$ns_theme_newage.website.paths.templateRootPath}FullWIdth.html
    }
    settings < plugin.ns_basetheme.settings
    settings.childSettings < plugin.ns_theme_newage.settings
  }
}

# Get default content
lib {

  copyright >
  copyright = COA
  copyright {
    stdWrap.wrap = |

    1 = TEXT
    1 {
    current = 1
    strftime = %Y
    wrap = &copy;&nbsp;|&nbsp;
    }

    2 = TEXT
    2 {
    value = {$ns_basetheme.website.settings.copyright}
    wrap = |
    }
  }

  # Remove breadcrumb from EXT:ns_basetheme
  breadcrumb >
  onePageScrollContent = CONTENT
  onePageScrollContent {
    table = pages
    select {
      languageField = sys_language_uid
      pidInList = {$ns_basetheme.website.settings.main_menu}               
      orderBy = sorting
    }
    renderObj = COA
    renderObj {
      5 = TEXT
      5 {
        field = title
        htmlSpecialChars = 1
        wrap = <section id='|'
        stdWrap.case = lower
        stdWrap.replacement {
          10 {
            search.char = 32
            replace.char = 45
          }
          15 {
            search = /
            replace = 
          }
        }
      }
      5.noTrimWrap = | | |


      6=FILES
      6.begin = 0
      6.maxItems = 1
      6.references.table = pages
      6.references.uid.data = uid
      6.references.fieldName  = media
      6.renderObj = TEXT
      6.renderObj {
        data = file:current:publicUrl
        wrap = style="background-image:url('|')"
      }
      6.noTrimWrap = | | | |

      7=TEXT
      7.value = >


      20 = CONTENT
      20 {
        table = tt_content
        select {
          languageField = sys_language_uid
          pidInList.field = uid
          orderBy = sorting
          where = colPos = 0
        }
        stdWrap.wrap = |</section>
        stdWrap.wrap.insertData = 1
      }
    }
  }
}



#Remove menu from EXT:ns_basetheme
menu {
  main >
  footer >
}
