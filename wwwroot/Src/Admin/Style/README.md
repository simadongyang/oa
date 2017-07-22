Base
base/文件夹包含了一些有关于你的项目中一些模板相关。在这里，你可以看到reset样式(或者Normalize.css,或者其他)，也有一些关于文本排版方面的，当然根据不同的项目会有一些其他的文件。

Helpers
helpers/文件夹（有的地方也称其为utils/）主要包含了项目中关于Sass的工具和帮助之类。在里面放置了我们需要使用的_function.scss，和_mixin.scss。在这里还包含了一个_variables.scss文件（有的地方也称其为_config.scss），这里包含项目中所有的全局变量（比如排版本上的，配色方案等等）。

Layout
layout/文件夹(有时也称为partials/)中放置了大量的文件，每个文件主要用于布局方面的，比如说"header"，“footer”等。他也会包括_grid.scss文件，用来创建网格系统。

Components
对于一些小组件，都放在了components/文件夹（通常也称为modules/），layout/是一个宏观的（定义全局的线框），components/是一个微观的。它里面放了一些特定的组件，比如说slider，loading，widget或者其他的小组件。通常components/目录下的都是一些小组件文件。
_admin_alert        悬浮弹出窗口

Page
如果你需要针对一些页面写特定的样式，我想将他们放在page/文件夹中是非常酷的，并且以页面的名称来命名。例如，你的首页需要制作一个特定的样式，那么你就可以在page/文件夹中创建一个名叫_home.scss文件。

Ui
Ui组件样式-文件对应js文件