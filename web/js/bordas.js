addEvent(window, 'load', initCorners);

  function initCorners() {
    var settings = {
      tl: { radius: 10 },
      tr: { radius: 10 },
      bl: { radius: 10 },
      br: { radius: 10 },
      antiAlias: true
    }
   
    var settingsDois = {
      tl: { radius: 10 },
      tr: { radius: 10 },
       antiAlias: true
    }
    var bt_top =
    {
      tl: { radius: 7 },
      tr: { radius: 7 },
      bl: { radius: 7 },
      br: { radius: 7 },
      antiAlias: true
    }
	var bordaBottom = {
		
		bl:{ radius:5},	br:{ radius:5}
		}
    /*
    Usage:

    curvyCorners(settingsObj, selectorStr);
    curvyCorners(settingsObj, Obj1[, Obj2[, Obj3[, . . . [, ObjN]]]]);

    selectorStr ::= complexSelector [, complexSelector]...
    complexSelector ::= singleSelector[ singleSelector]
    singleSelector ::= idType | classType
    idType ::= #id
    classType ::= [tagName].className
    tagName ::= div|p|form|blockquote|frameset // others may work
    className : .name
    selector examples:
      #mydiv p.rounded
      #mypara
      .rounded
    */
      // curvyCorners(bt_top, ".bt_top");
       curvyCorners(settingsDois, "#header");
       curvyCorners(settings, ".display_meta");
       curvyCorners(settings, ".display_pedidos");
   	 }