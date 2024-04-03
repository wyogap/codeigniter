
{if isset($crud.custom_js)}
<script type="text/javascript" defer>
    {$crud.custom_js}
</script>
{/if}

<script type="text/javascript" defer>

    var base_url = "{$base_url}";
    var site_url = "{$site_url}";

    var level1_name = "{if !empty($level1_name)}{$level1_name}{/if}";
    var level1_id = "{if !empty($level1_id)}{$level1_id}{/if}";

    function update_footer(api, colIdx, colName, colType = null) {
        
        //var api = this.api(), data;
        //columnName = 4;
    
        col = api.column( colIdx);
        if (col.length == 0)    return;

        //$( api.column( 0 ).footer() ).html("Sum"); 


        // Remove the formatting to get integer data for summation
        var intVal = function ( i ) {
            return typeof i === 'string' ?
                i.replace(/[\$,]/g, '')*1 :
                typeof i === 'number' ?
                    i : 0;
        };

        // Total over all pages
        total = api
            .column( colIdx, { search: 'applied' } )
            .data()
            .reduce( function (a, b) {
                return intVal(a) + intVal(b);
            }, 0 );

        // Total over this page
        pageTotal = api
            .column( colIdx, { page: 'current'} )
            .data()
            .reduce( function (a, b) {
                return intVal(a) + intVal(b);
            }, 0 );

        // Total over this page
        selectedTotal = pageTotal;
        // colname = api.columns();

        data = api.rows({ selected: true}).data().pluck(colName);
        if (data.length > 0) {
            selectedTotal = data.reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );    
        }

        // Update footer
        if (selectedTotal==0 && total==0) {
            $( api.column( colIdx ).footer() ).html("-");
        }
        else {
            if (colType == "tcg_currency") {
                selectedTotal = $.fn.dataTable.render.number('{$currency_thousand_separator}', '{$currency_decimal_separator}', {$currency_decimal_precision}, '{$currency_prefix}').display(selectedTotal);
                total = $.fn.dataTable.render.number('{$currency_thousand_separator}', '{$currency_decimal_separator}', {$currency_decimal_precision}, '{$currency_prefix}').display(total);
            }

            $( api.column( colIdx ).footer() ).html(
                selectedTotal +'<br>('+ total +')'
            );  
        }
    
    }

    function toColumnName(num) {
        for (var ret = '', a = 1, b = 26; (num -= a) >= 0; a = b, b *= 26) {
            ret = String.fromCharCode(parseInt((num % b) / a) + 65) + ret;
        }
        return ret;
    }

    function throttle(func, wait, options) {
        var timeout, context, args, result;
        var previous = 0;
        if (!options) options = {};

        var later = function() {
            previous = options.leading === false ? 0 : now();
            timeout = null;
            result = func.apply(context, args);
            if (!timeout) context = args = null;
        };

        var throttled = function() {
            var _now = now();
            if (!previous && options.leading === false) previous = _now;
            var remaining = wait - (_now - previous);
            context = this;
            args = arguments;
            if (remaining <= 0 || remaining > wait) {
            if (timeout) {
                clearTimeout(timeout);
                timeout = null;
            }
            previous = _now;
            result = func.apply(context, args);
            if (!timeout) context = args = null;
            } else if (!timeout && options.trailing !== false) {
            timeout = setTimeout(later, remaining);
            }
            return result;
        };

        throttled.cancel = function() {
            clearTimeout(timeout);
            previous = 0;
            timeout = context = args = null;
        };

        return throttled;
    }

    function restArguments(func, startIndex) {
        startIndex = startIndex == null ? func.length - 1 : +startIndex;

        return function() {
            var length = Math.max(arguments.length - startIndex, 0),
                rest = Array(length),
                index = 0;
            for (; index < length; index++) {
                rest[index] = arguments[index + startIndex];
            }
            switch (startIndex) {
                case 0: return func.call(this, rest);
                case 1: return func.call(this, arguments[0], rest);
                case 2: return func.call(this, arguments[0], arguments[1], rest);
            }
            var args = Array(startIndex + 1);
                for (index = 0; index < startIndex; index++) {
                args[index] = arguments[index];
            }
            args[startIndex] = rest;
            return func.apply(this, args);
        };
    };

    function now() {
        return new Date().getTime();
    };

    function debounce(func, wait, immediate) {
        var timeout, previous, args, result, context;

        var later = function() {
            var passed = now() - previous;
            if (wait > passed) {
                // new call while the existing call is executing -> schedule for latter
                timeout = setTimeout(later, wait - passed);
            } else {
                timeout = null;
                if (!immediate) result = func.apply(context, args);
                if (!timeout) args = context = null;
            }
        };

        var debounced = restArguments(function(_args) {
            context = this;
            args = _args;
            previous = now();
            if (!timeout) {
                timeout = setTimeout(later, wait);
                if (immediate) result = func.apply(context, args);
            }
            return result;
        });

        debounced.cancel = function() {
            clearTimeout(timeout);
            timeout = args = context = null;
        };

        return debounced;
    }

</script>