//Code derived from https://gist.github.com/joelfi/6572692aa0261200912ee61c303e7308 - Released under the MIT License
/*
 * Copyright © 2017 JoelFI
 
 Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the “Software”), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:
 
 The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.
 
 THE SOFTWARE IS PROVIDED “AS IS”, WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
 */
$(function () {
    var languageCache = {}; // caching for languages, prevents loading language from server every time user changes language
    // you could also store this in localStorage for super-caching

    var changeLanguage = function (lang) { // changes language
        var processData = function (data) { // processes the data
            $.each(data, function (selector, value) { // loops through everything using jQuery (supports older browsers)
                $("[data-language='" + selector + "']").each(function () { // loops through all elements with the specific language selector (warn: do not use ' or escape ' and \)
                    if ($(this).data("language-target-attr")) { // checks if the element wants the language in an attribute (i.e. value, placeholder)
                        $(this).prop($(this).data("language-target-attr"), value); // adds the value to requested attribute: does not check if valid HTML to add that attribute
                    } else { // no special requests
                        $(this).html(value); // changes the inner HTML, could use .text() too if risk for XSS (i.e. user-supplied data)
                    }
                });
            });
        };

        if (Object.keys(languageCache).indexOf(lang) > -1) { // checks for language in cache
            processData(languageCache[lang]); // processes language data from cache
        } else { // no data in cache for that language
            $.getJSON("/lang/lang-" + lang + ".json?_=" + Date.now(), function (data) { // gets JSON with specific language
                languageCache[lang] = data; // adds to cache
                processData(data); // processes loaded data
            });
        }
    };

    changeLanguage("en"); // default set (could get from localStorage or browser information (headers, options))

    $.getJSON("/lang/lang-languages.json", function (data) { // fetches all language names
        $.each(data, function (short, full) { // loops through all languages, using jQuery to support most browsers (old Android)
            $("<a href=\"#\" data-language-id=\"" + short + "\" style=\"margin-right: 5px;\">" + full + "</a>").on('click', function () { // creates a link and initiates a click event handler
                changeLanguage($(this).data("language-id")); // calls to change language with data-language-id: do not use "short" variable since this is inside a loop
            }).appendTo("#languages"); // appends link to #languages
        });
    });
});