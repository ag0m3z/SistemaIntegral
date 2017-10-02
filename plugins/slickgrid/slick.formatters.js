/***
 * Contains basic SlickGrid formatters.
 * 
 * NOTE:  These are merely examples.  You will most likely need to implement something more
 *        robust/extensible/localizable/etc. for your use!
 * 
 * @module Formatters
 * @namespace Slick
 */

(function ($) {
  // register namespace
  $.extend(true, window, {
    "Slick": {
      "Formatters": {
        "PercentComplete": PercentCompleteFormatter,
        "PercentCompleteBar": PercentCompleteBarFormatter,
        "YesNo": YesNoFormatter,
        "Checkmark": CheckmarkFormatter,
        "CurrencyFormatter": CurrencyFormatter,
          "SelectCellEditor": SelectCellEditor
      }
    }
  });

  function PercentCompleteFormatter(row, cell, value, columnDef, dataContext) {
    if (value == null || value === "") {
      return "-";
    } else if (value < 50) {
      return "<span style='color:red;font-weight:bold;'>" + value + "%</span>";
    } else {
      return "<span style='color:green'>" + value + "%</span>";
    }
  }

  function PercentCompleteBarFormatter(row, cell, value, columnDef, dataContext) {
    if (value == null || value === "") {
      return "";
    }

    var color;

    if (value < 30) {
      color = "red";
    } else if (value < 70) {
      color = "silver";
    } else {
      color = "green";
    }

    return "<span class='percent-complete-bar' style='background:" + color + ";width:" + value + "%'></span>";
  }

  function YesNoFormatter(row, cell, value, columnDef, dataContext) {
    return value ? "Yes" : "No";
  }

  function CheckmarkFormatter(row, cell, value, columnDef, dataContext) {
    return value ? "<img src='../images/tick.png'>" : "";
  }

    function CurrencyFormatter(row, cell, cnt, columnDef, dataContext) {

        var cents = true;

        cnt = cnt.toString().replace(/\$|\u20AC|\,/g,'');
        if (isNaN(cnt))
            return 0;
        var sgn = (cnt == (cnt = Math.abs(cnt)));
        cnt = Math.floor(cnt * 100 + 0.5);
        cvs = cnt % 100;
        cnt = Math.floor(cnt / 100).toString();
        if (cvs < 10)
            cvs = '0' + cvs;
        for (var i = 0; i < Math.floor((cnt.length - (1 + i)) / 3); i++)
            cnt = cnt.substring(0, cnt.length - (4 * i + 3)) + ',' + cnt.substring(cnt.length - (4 * i + 3));



        if(cnt <= '0'){
            return "<span class='text-gray'> $ "+ (((sgn) ? '' : '-') + cnt) + ( cents ?  '.' + cvs : ''); +"</span>";
        }else{
            return "$ "+ (((sgn) ? '' : '-') + cnt) + ( cents ?  '.' + cvs : '');;
        }
    }

    function SelectCellEditor(args) {
        var $select;
        var defaultValue;
        var scope = this;

        this.init = function() {

            if(args.column.options){
                opt_values = args.column.options.split(',');
            }else{
                opt_values ="yes,no".split(',');
            }
            option_str = ""
            for( i in opt_values ){
                v = opt_values[i];
                option_str += "<OPTION value='"+v+"'>"+v+"</OPTION>";
            }
            $select = $("<SELECT tabIndex='0' class='editor-select'>"+ option_str +"</SELECT>");
            $select.appendTo(args.container);
            $select.focus();
        };

        this.destroy = function() {
            $select.remove();
        };

        this.focus = function() {
            $select.focus();
        };

        this.loadValue = function(item) {
            defaultValue = item[args.column.field];
            $select.val(defaultValue);
        };

        this.serializeValue = function() {
            if(args.column.options){
                return $select.val();
            }else{
                return ($select.val() == "yes");
            }
        };

        this.applyValue = function(item,state) {
            item[args.column.field] = state;
        };

        this.isValueChanged = function() {
            return ($select.val() != defaultValue);
        };

        this.validate = function() {
            return {
                valid: true,
                msg: null
            };
        };

        this.init();
    }


})(jQuery);
