/******/ (() => { // webpackBootstrap
/******/ 	var __webpack_modules__ = ({

/***/ "./node_modules/object-assign/index.js":
/*!*********************************************!*\
  !*** ./node_modules/object-assign/index.js ***!
  \*********************************************/
/***/ ((module) => {

"use strict";
/*
object-assign
(c) Sindre Sorhus
@license MIT
*/


/* eslint-disable no-unused-vars */
var getOwnPropertySymbols = Object.getOwnPropertySymbols;
var hasOwnProperty = Object.prototype.hasOwnProperty;
var propIsEnumerable = Object.prototype.propertyIsEnumerable;

function toObject(val) {
	if (val === null || val === undefined) {
		throw new TypeError('Object.assign cannot be called with null or undefined');
	}

	return Object(val);
}

function shouldUseNative() {
	try {
		if (!Object.assign) {
			return false;
		}

		// Detect buggy property enumeration order in older V8 versions.

		// https://bugs.chromium.org/p/v8/issues/detail?id=4118
		var test1 = new String('abc');  // eslint-disable-line no-new-wrappers
		test1[5] = 'de';
		if (Object.getOwnPropertyNames(test1)[0] === '5') {
			return false;
		}

		// https://bugs.chromium.org/p/v8/issues/detail?id=3056
		var test2 = {};
		for (var i = 0; i < 10; i++) {
			test2['_' + String.fromCharCode(i)] = i;
		}
		var order2 = Object.getOwnPropertyNames(test2).map(function (n) {
			return test2[n];
		});
		if (order2.join('') !== '0123456789') {
			return false;
		}

		// https://bugs.chromium.org/p/v8/issues/detail?id=3056
		var test3 = {};
		'abcdefghijklmnopqrst'.split('').forEach(function (letter) {
			test3[letter] = letter;
		});
		if (Object.keys(Object.assign({}, test3)).join('') !==
				'abcdefghijklmnopqrst') {
			return false;
		}

		return true;
	} catch (err) {
		// We don't expect any of the above to throw, but better to be safe.
		return false;
	}
}

module.exports = shouldUseNative() ? Object.assign : function (target, source) {
	var from;
	var to = toObject(target);
	var symbols;

	for (var s = 1; s < arguments.length; s++) {
		from = Object(arguments[s]);

		for (var key in from) {
			if (hasOwnProperty.call(from, key)) {
				to[key] = from[key];
			}
		}

		if (getOwnPropertySymbols) {
			symbols = getOwnPropertySymbols(from);
			for (var i = 0; i < symbols.length; i++) {
				if (propIsEnumerable.call(from, symbols[i])) {
					to[symbols[i]] = from[symbols[i]];
				}
			}
		}
	}

	return to;
};


/***/ }),

/***/ "./node_modules/prop-types/checkPropTypes.js":
/*!***************************************************!*\
  !*** ./node_modules/prop-types/checkPropTypes.js ***!
  \***************************************************/
/***/ ((module, __unused_webpack_exports, __webpack_require__) => {

"use strict";
/**
 * Copyright (c) 2013-present, Facebook, Inc.
 *
 * This source code is licensed under the MIT license found in the
 * LICENSE file in the root directory of this source tree.
 */



var printWarning = function() {};

if (true) {
  var ReactPropTypesSecret = __webpack_require__(Object(function webpackMissingModule() { var e = new Error("Cannot find module './lib/ReactPropTypesSecret'"); e.code = 'MODULE_NOT_FOUND'; throw e; }()));
  var loggedTypeFailures = {};
  var has = __webpack_require__(/*! ./lib/has */ "./node_modules/prop-types/lib/has.js");

  printWarning = function(text) {
    var message = 'Warning: ' + text;
    if (typeof console !== 'undefined') {
      console.error(message);
    }
    try {
      // --- Welcome to debugging React ---
      // This error was thrown as a convenience so that you can use this stack
      // to find the callsite that caused this warning to fire.
      throw new Error(message);
    } catch (x) { /**/ }
  };
}

/**
 * Assert that the values match with the type specs.
 * Error messages are memorized and will only be shown once.
 *
 * @param {object} typeSpecs Map of name to a ReactPropType
 * @param {object} values Runtime values that need to be type-checked
 * @param {string} location e.g. "prop", "context", "child context"
 * @param {string} componentName Name of the component for error messages.
 * @param {?Function} getStack Returns the component stack.
 * @private
 */
function checkPropTypes(typeSpecs, values, location, componentName, getStack) {
  if (true) {
    for (var typeSpecName in typeSpecs) {
      if (has(typeSpecs, typeSpecName)) {
        var error;
        // Prop type validation may throw. In case they do, we don't want to
        // fail the render phase where it didn't fail before. So we log it.
        // After these have been cleaned up, we'll let them throw.
        try {
          // This is intentionally an invariant that gets caught. It's the same
          // behavior as without this statement except with a better message.
          if (typeof typeSpecs[typeSpecName] !== 'function') {
            var err = Error(
              (componentName || 'React class') + ': ' + location + ' type `' + typeSpecName + '` is invalid; ' +
              'it must be a function, usually from the `prop-types` package, but received `' + typeof typeSpecs[typeSpecName] + '`.' +
              'This often happens because of typos such as `PropTypes.function` instead of `PropTypes.func`.'
            );
            err.name = 'Invariant Violation';
            throw err;
          }
          error = typeSpecs[typeSpecName](values, typeSpecName, componentName, location, null, ReactPropTypesSecret);
        } catch (ex) {
          error = ex;
        }
        if (error && !(error instanceof Error)) {
          printWarning(
            (componentName || 'React class') + ': type specification of ' +
            location + ' `' + typeSpecName + '` is invalid; the type checker ' +
            'function must return `null` or an `Error` but returned a ' + typeof error + '. ' +
            'You may have forgotten to pass an argument to the type checker ' +
            'creator (arrayOf, instanceOf, objectOf, oneOf, oneOfType, and ' +
            'shape all require an argument).'
          );
        }
        if (error instanceof Error && !(error.message in loggedTypeFailures)) {
          // Only monitor this failure once because there tends to be a lot of the
          // same error.
          loggedTypeFailures[error.message] = true;

          var stack = getStack ? getStack() : '';

          printWarning(
            'Failed ' + location + ' type: ' + error.message + (stack != null ? stack : '')
          );
        }
      }
    }
  }
}

/**
 * Resets warning cache when testing.
 *
 * @private
 */
checkPropTypes.resetWarningCache = function() {
  if (true) {
    loggedTypeFailures = {};
  }
}

module.exports = checkPropTypes;


/***/ }),

/***/ "./node_modules/prop-types/factoryWithTypeCheckers.js":
/*!************************************************************!*\
  !*** ./node_modules/prop-types/factoryWithTypeCheckers.js ***!
  \************************************************************/
/***/ ((module, __unused_webpack_exports, __webpack_require__) => {

"use strict";
/**
 * Copyright (c) 2013-present, Facebook, Inc.
 *
 * This source code is licensed under the MIT license found in the
 * LICENSE file in the root directory of this source tree.
 */



var ReactIs = __webpack_require__(/*! react-is */ "./node_modules/prop-types/node_modules/react-is/index.js");
var assign = __webpack_require__(/*! object-assign */ "./node_modules/object-assign/index.js");

var ReactPropTypesSecret = __webpack_require__(Object(function webpackMissingModule() { var e = new Error("Cannot find module './lib/ReactPropTypesSecret'"); e.code = 'MODULE_NOT_FOUND'; throw e; }()));
var has = __webpack_require__(/*! ./lib/has */ "./node_modules/prop-types/lib/has.js");
var checkPropTypes = __webpack_require__(/*! ./checkPropTypes */ "./node_modules/prop-types/checkPropTypes.js");

var printWarning = function() {};

if (true) {
  printWarning = function(text) {
    var message = 'Warning: ' + text;
    if (typeof console !== 'undefined') {
      console.error(message);
    }
    try {
      // --- Welcome to debugging React ---
      // This error was thrown as a convenience so that you can use this stack
      // to find the callsite that caused this warning to fire.
      throw new Error(message);
    } catch (x) {}
  };
}

function emptyFunctionThatReturnsNull() {
  return null;
}

module.exports = function(isValidElement, throwOnDirectAccess) {
  /* global Symbol */
  var ITERATOR_SYMBOL = typeof Symbol === 'function' && Symbol.iterator;
  var FAUX_ITERATOR_SYMBOL = '@@iterator'; // Before Symbol spec.

  /**
   * Returns the iterator method function contained on the iterable object.
   *
   * Be sure to invoke the function with the iterable as context:
   *
   *     var iteratorFn = getIteratorFn(myIterable);
   *     if (iteratorFn) {
   *       var iterator = iteratorFn.call(myIterable);
   *       ...
   *     }
   *
   * @param {?object} maybeIterable
   * @return {?function}
   */
  function getIteratorFn(maybeIterable) {
    var iteratorFn = maybeIterable && (ITERATOR_SYMBOL && maybeIterable[ITERATOR_SYMBOL] || maybeIterable[FAUX_ITERATOR_SYMBOL]);
    if (typeof iteratorFn === 'function') {
      return iteratorFn;
    }
  }

  /**
   * Collection of methods that allow declaration and validation of props that are
   * supplied to React components. Example usage:
   *
   *   var Props = require('ReactPropTypes');
   *   var MyArticle = React.createClass({
   *     propTypes: {
   *       // An optional string prop named "description".
   *       description: Props.string,
   *
   *       // A required enum prop named "category".
   *       category: Props.oneOf(['News','Photos']).isRequired,
   *
   *       // A prop named "dialog" that requires an instance of Dialog.
   *       dialog: Props.instanceOf(Dialog).isRequired
   *     },
   *     render: function() { ... }
   *   });
   *
   * A more formal specification of how these methods are used:
   *
   *   type := array|bool|func|object|number|string|oneOf([...])|instanceOf(...)
   *   decl := ReactPropTypes.{type}(.isRequired)?
   *
   * Each and every declaration produces a function with the same signature. This
   * allows the creation of custom validation functions. For example:
   *
   *  var MyLink = React.createClass({
   *    propTypes: {
   *      // An optional string or URI prop named "href".
   *      href: function(props, propName, componentName) {
   *        var propValue = props[propName];
   *        if (propValue != null && typeof propValue !== 'string' &&
   *            !(propValue instanceof URI)) {
   *          return new Error(
   *            'Expected a string or an URI for ' + propName + ' in ' +
   *            componentName
   *          );
   *        }
   *      }
   *    },
   *    render: function() {...}
   *  });
   *
   * @internal
   */

  var ANONYMOUS = '<<anonymous>>';

  // Important!
  // Keep this list in sync with production version in `./factoryWithThrowingShims.js`.
  var ReactPropTypes = {
    array: createPrimitiveTypeChecker('array'),
    bigint: createPrimitiveTypeChecker('bigint'),
    bool: createPrimitiveTypeChecker('boolean'),
    func: createPrimitiveTypeChecker('function'),
    number: createPrimitiveTypeChecker('number'),
    object: createPrimitiveTypeChecker('object'),
    string: createPrimitiveTypeChecker('string'),
    symbol: createPrimitiveTypeChecker('symbol'),

    any: createAnyTypeChecker(),
    arrayOf: createArrayOfTypeChecker,
    element: createElementTypeChecker(),
    elementType: createElementTypeTypeChecker(),
    instanceOf: createInstanceTypeChecker,
    node: createNodeChecker(),
    objectOf: createObjectOfTypeChecker,
    oneOf: createEnumTypeChecker,
    oneOfType: createUnionTypeChecker,
    shape: createShapeTypeChecker,
    exact: createStrictShapeTypeChecker,
  };

  /**
   * inlined Object.is polyfill to avoid requiring consumers ship their own
   * https://developer.mozilla.org/en-US/docs/Web/JavaScript/Reference/Global_Objects/Object/is
   */
  /*eslint-disable no-self-compare*/
  function is(x, y) {
    // SameValue algorithm
    if (x === y) {
      // Steps 1-5, 7-10
      // Steps 6.b-6.e: +0 != -0
      return x !== 0 || 1 / x === 1 / y;
    } else {
      // Step 6.a: NaN == NaN
      return x !== x && y !== y;
    }
  }
  /*eslint-enable no-self-compare*/

  /**
   * We use an Error-like object for backward compatibility as people may call
   * PropTypes directly and inspect their output. However, we don't use real
   * Errors anymore. We don't inspect their stack anyway, and creating them
   * is prohibitively expensive if they are created too often, such as what
   * happens in oneOfType() for any type before the one that matched.
   */
  function PropTypeError(message, data) {
    this.message = message;
    this.data = data && typeof data === 'object' ? data: {};
    this.stack = '';
  }
  // Make `instanceof Error` still work for returned errors.
  PropTypeError.prototype = Error.prototype;

  function createChainableTypeChecker(validate) {
    if (true) {
      var manualPropTypeCallCache = {};
      var manualPropTypeWarningCount = 0;
    }
    function checkType(isRequired, props, propName, componentName, location, propFullName, secret) {
      componentName = componentName || ANONYMOUS;
      propFullName = propFullName || propName;

      if (secret !== ReactPropTypesSecret) {
        if (throwOnDirectAccess) {
          // New behavior only for users of `prop-types` package
          var err = new Error(
            'Calling PropTypes validators directly is not supported by the `prop-types` package. ' +
            'Use `PropTypes.checkPropTypes()` to call them. ' +
            'Read more at http://fb.me/use-check-prop-types'
          );
          err.name = 'Invariant Violation';
          throw err;
        } else if ( true && typeof console !== 'undefined') {
          // Old behavior for people using React.PropTypes
          var cacheKey = componentName + ':' + propName;
          if (
            !manualPropTypeCallCache[cacheKey] &&
            // Avoid spamming the console because they are often not actionable except for lib authors
            manualPropTypeWarningCount < 3
          ) {
            printWarning(
              'You are manually calling a React.PropTypes validation ' +
              'function for the `' + propFullName + '` prop on `' + componentName + '`. This is deprecated ' +
              'and will throw in the standalone `prop-types` package. ' +
              'You may be seeing this warning due to a third-party PropTypes ' +
              'library. See https://fb.me/react-warning-dont-call-proptypes ' + 'for details.'
            );
            manualPropTypeCallCache[cacheKey] = true;
            manualPropTypeWarningCount++;
          }
        }
      }
      if (props[propName] == null) {
        if (isRequired) {
          if (props[propName] === null) {
            return new PropTypeError('The ' + location + ' `' + propFullName + '` is marked as required ' + ('in `' + componentName + '`, but its value is `null`.'));
          }
          return new PropTypeError('The ' + location + ' `' + propFullName + '` is marked as required in ' + ('`' + componentName + '`, but its value is `undefined`.'));
        }
        return null;
      } else {
        return validate(props, propName, componentName, location, propFullName);
      }
    }

    var chainedCheckType = checkType.bind(null, false);
    chainedCheckType.isRequired = checkType.bind(null, true);

    return chainedCheckType;
  }

  function createPrimitiveTypeChecker(expectedType) {
    function validate(props, propName, componentName, location, propFullName, secret) {
      var propValue = props[propName];
      var propType = getPropType(propValue);
      if (propType !== expectedType) {
        // `propValue` being instance of, say, date/regexp, pass the 'object'
        // check, but we can offer a more precise error message here rather than
        // 'of type `object`'.
        var preciseType = getPreciseType(propValue);

        return new PropTypeError(
          'Invalid ' + location + ' `' + propFullName + '` of type ' + ('`' + preciseType + '` supplied to `' + componentName + '`, expected ') + ('`' + expectedType + '`.'),
          {expectedType: expectedType}
        );
      }
      return null;
    }
    return createChainableTypeChecker(validate);
  }

  function createAnyTypeChecker() {
    return createChainableTypeChecker(emptyFunctionThatReturnsNull);
  }

  function createArrayOfTypeChecker(typeChecker) {
    function validate(props, propName, componentName, location, propFullName) {
      if (typeof typeChecker !== 'function') {
        return new PropTypeError('Property `' + propFullName + '` of component `' + componentName + '` has invalid PropType notation inside arrayOf.');
      }
      var propValue = props[propName];
      if (!Array.isArray(propValue)) {
        var propType = getPropType(propValue);
        return new PropTypeError('Invalid ' + location + ' `' + propFullName + '` of type ' + ('`' + propType + '` supplied to `' + componentName + '`, expected an array.'));
      }
      for (var i = 0; i < propValue.length; i++) {
        var error = typeChecker(propValue, i, componentName, location, propFullName + '[' + i + ']', ReactPropTypesSecret);
        if (error instanceof Error) {
          return error;
        }
      }
      return null;
    }
    return createChainableTypeChecker(validate);
  }

  function createElementTypeChecker() {
    function validate(props, propName, componentName, location, propFullName) {
      var propValue = props[propName];
      if (!isValidElement(propValue)) {
        var propType = getPropType(propValue);
        return new PropTypeError('Invalid ' + location + ' `' + propFullName + '` of type ' + ('`' + propType + '` supplied to `' + componentName + '`, expected a single ReactElement.'));
      }
      return null;
    }
    return createChainableTypeChecker(validate);
  }

  function createElementTypeTypeChecker() {
    function validate(props, propName, componentName, location, propFullName) {
      var propValue = props[propName];
      if (!ReactIs.isValidElementType(propValue)) {
        var propType = getPropType(propValue);
        return new PropTypeError('Invalid ' + location + ' `' + propFullName + '` of type ' + ('`' + propType + '` supplied to `' + componentName + '`, expected a single ReactElement type.'));
      }
      return null;
    }
    return createChainableTypeChecker(validate);
  }

  function createInstanceTypeChecker(expectedClass) {
    function validate(props, propName, componentName, location, propFullName) {
      if (!(props[propName] instanceof expectedClass)) {
        var expectedClassName = expectedClass.name || ANONYMOUS;
        var actualClassName = getClassName(props[propName]);
        return new PropTypeError('Invalid ' + location + ' `' + propFullName + '` of type ' + ('`' + actualClassName + '` supplied to `' + componentName + '`, expected ') + ('instance of `' + expectedClassName + '`.'));
      }
      return null;
    }
    return createChainableTypeChecker(validate);
  }

  function createEnumTypeChecker(expectedValues) {
    if (!Array.isArray(expectedValues)) {
      if (true) {
        if (arguments.length > 1) {
          printWarning(
            'Invalid arguments supplied to oneOf, expected an array, got ' + arguments.length + ' arguments. ' +
            'A common mistake is to write oneOf(x, y, z) instead of oneOf([x, y, z]).'
          );
        } else {
          printWarning('Invalid argument supplied to oneOf, expected an array.');
        }
      }
      return emptyFunctionThatReturnsNull;
    }

    function validate(props, propName, componentName, location, propFullName) {
      var propValue = props[propName];
      for (var i = 0; i < expectedValues.length; i++) {
        if (is(propValue, expectedValues[i])) {
          return null;
        }
      }

      var valuesString = JSON.stringify(expectedValues, function replacer(key, value) {
        var type = getPreciseType(value);
        if (type === 'symbol') {
          return String(value);
        }
        return value;
      });
      return new PropTypeError('Invalid ' + location + ' `' + propFullName + '` of value `' + String(propValue) + '` ' + ('supplied to `' + componentName + '`, expected one of ' + valuesString + '.'));
    }
    return createChainableTypeChecker(validate);
  }

  function createObjectOfTypeChecker(typeChecker) {
    function validate(props, propName, componentName, location, propFullName) {
      if (typeof typeChecker !== 'function') {
        return new PropTypeError('Property `' + propFullName + '` of component `' + componentName + '` has invalid PropType notation inside objectOf.');
      }
      var propValue = props[propName];
      var propType = getPropType(propValue);
      if (propType !== 'object') {
        return new PropTypeError('Invalid ' + location + ' `' + propFullName + '` of type ' + ('`' + propType + '` supplied to `' + componentName + '`, expected an object.'));
      }
      for (var key in propValue) {
        if (has(propValue, key)) {
          var error = typeChecker(propValue, key, componentName, location, propFullName + '.' + key, ReactPropTypesSecret);
          if (error instanceof Error) {
            return error;
          }
        }
      }
      return null;
    }
    return createChainableTypeChecker(validate);
  }

  function createUnionTypeChecker(arrayOfTypeCheckers) {
    if (!Array.isArray(arrayOfTypeCheckers)) {
       true ? printWarning('Invalid argument supplied to oneOfType, expected an instance of array.') : 0;
      return emptyFunctionThatReturnsNull;
    }

    for (var i = 0; i < arrayOfTypeCheckers.length; i++) {
      var checker = arrayOfTypeCheckers[i];
      if (typeof checker !== 'function') {
        printWarning(
          'Invalid argument supplied to oneOfType. Expected an array of check functions, but ' +
          'received ' + getPostfixForTypeWarning(checker) + ' at index ' + i + '.'
        );
        return emptyFunctionThatReturnsNull;
      }
    }

    function validate(props, propName, componentName, location, propFullName) {
      var expectedTypes = [];
      for (var i = 0; i < arrayOfTypeCheckers.length; i++) {
        var checker = arrayOfTypeCheckers[i];
        var checkerResult = checker(props, propName, componentName, location, propFullName, ReactPropTypesSecret);
        if (checkerResult == null) {
          return null;
        }
        if (checkerResult.data && has(checkerResult.data, 'expectedType')) {
          expectedTypes.push(checkerResult.data.expectedType);
        }
      }
      var expectedTypesMessage = (expectedTypes.length > 0) ? ', expected one of type [' + expectedTypes.join(', ') + ']': '';
      return new PropTypeError('Invalid ' + location + ' `' + propFullName + '` supplied to ' + ('`' + componentName + '`' + expectedTypesMessage + '.'));
    }
    return createChainableTypeChecker(validate);
  }

  function createNodeChecker() {
    function validate(props, propName, componentName, location, propFullName) {
      if (!isNode(props[propName])) {
        return new PropTypeError('Invalid ' + location + ' `' + propFullName + '` supplied to ' + ('`' + componentName + '`, expected a ReactNode.'));
      }
      return null;
    }
    return createChainableTypeChecker(validate);
  }

  function invalidValidatorError(componentName, location, propFullName, key, type) {
    return new PropTypeError(
      (componentName || 'React class') + ': ' + location + ' type `' + propFullName + '.' + key + '` is invalid; ' +
      'it must be a function, usually from the `prop-types` package, but received `' + type + '`.'
    );
  }

  function createShapeTypeChecker(shapeTypes) {
    function validate(props, propName, componentName, location, propFullName) {
      var propValue = props[propName];
      var propType = getPropType(propValue);
      if (propType !== 'object') {
        return new PropTypeError('Invalid ' + location + ' `' + propFullName + '` of type `' + propType + '` ' + ('supplied to `' + componentName + '`, expected `object`.'));
      }
      for (var key in shapeTypes) {
        var checker = shapeTypes[key];
        if (typeof checker !== 'function') {
          return invalidValidatorError(componentName, location, propFullName, key, getPreciseType(checker));
        }
        var error = checker(propValue, key, componentName, location, propFullName + '.' + key, ReactPropTypesSecret);
        if (error) {
          return error;
        }
      }
      return null;
    }
    return createChainableTypeChecker(validate);
  }

  function createStrictShapeTypeChecker(shapeTypes) {
    function validate(props, propName, componentName, location, propFullName) {
      var propValue = props[propName];
      var propType = getPropType(propValue);
      if (propType !== 'object') {
        return new PropTypeError('Invalid ' + location + ' `' + propFullName + '` of type `' + propType + '` ' + ('supplied to `' + componentName + '`, expected `object`.'));
      }
      // We need to check all keys in case some are required but missing from props.
      var allKeys = assign({}, props[propName], shapeTypes);
      for (var key in allKeys) {
        var checker = shapeTypes[key];
        if (has(shapeTypes, key) && typeof checker !== 'function') {
          return invalidValidatorError(componentName, location, propFullName, key, getPreciseType(checker));
        }
        if (!checker) {
          return new PropTypeError(
            'Invalid ' + location + ' `' + propFullName + '` key `' + key + '` supplied to `' + componentName + '`.' +
            '\nBad object: ' + JSON.stringify(props[propName], null, '  ') +
            '\nValid keys: ' + JSON.stringify(Object.keys(shapeTypes), null, '  ')
          );
        }
        var error = checker(propValue, key, componentName, location, propFullName + '.' + key, ReactPropTypesSecret);
        if (error) {
          return error;
        }
      }
      return null;
    }

    return createChainableTypeChecker(validate);
  }

  function isNode(propValue) {
    switch (typeof propValue) {
      case 'number':
      case 'string':
      case 'undefined':
        return true;
      case 'boolean':
        return !propValue;
      case 'object':
        if (Array.isArray(propValue)) {
          return propValue.every(isNode);
        }
        if (propValue === null || isValidElement(propValue)) {
          return true;
        }

        var iteratorFn = getIteratorFn(propValue);
        if (iteratorFn) {
          var iterator = iteratorFn.call(propValue);
          var step;
          if (iteratorFn !== propValue.entries) {
            while (!(step = iterator.next()).done) {
              if (!isNode(step.value)) {
                return false;
              }
            }
          } else {
            // Iterator will provide entry [k,v] tuples rather than values.
            while (!(step = iterator.next()).done) {
              var entry = step.value;
              if (entry) {
                if (!isNode(entry[1])) {
                  return false;
                }
              }
            }
          }
        } else {
          return false;
        }

        return true;
      default:
        return false;
    }
  }

  function isSymbol(propType, propValue) {
    // Native Symbol.
    if (propType === 'symbol') {
      return true;
    }

    // falsy value can't be a Symbol
    if (!propValue) {
      return false;
    }

    // 19.4.3.5 Symbol.prototype[@@toStringTag] === 'Symbol'
    if (propValue['@@toStringTag'] === 'Symbol') {
      return true;
    }

    // Fallback for non-spec compliant Symbols which are polyfilled.
    if (typeof Symbol === 'function' && propValue instanceof Symbol) {
      return true;
    }

    return false;
  }

  // Equivalent of `typeof` but with special handling for array and regexp.
  function getPropType(propValue) {
    var propType = typeof propValue;
    if (Array.isArray(propValue)) {
      return 'array';
    }
    if (propValue instanceof RegExp) {
      // Old webkits (at least until Android 4.0) return 'function' rather than
      // 'object' for typeof a RegExp. We'll normalize this here so that /bla/
      // passes PropTypes.object.
      return 'object';
    }
    if (isSymbol(propType, propValue)) {
      return 'symbol';
    }
    return propType;
  }

  // This handles more types than `getPropType`. Only used for error messages.
  // See `createPrimitiveTypeChecker`.
  function getPreciseType(propValue) {
    if (typeof propValue === 'undefined' || propValue === null) {
      return '' + propValue;
    }
    var propType = getPropType(propValue);
    if (propType === 'object') {
      if (propValue instanceof Date) {
        return 'date';
      } else if (propValue instanceof RegExp) {
        return 'regexp';
      }
    }
    return propType;
  }

  // Returns a string that is postfixed to a warning about an invalid type.
  // For example, "undefined" or "of type array"
  function getPostfixForTypeWarning(value) {
    var type = getPreciseType(value);
    switch (type) {
      case 'array':
      case 'object':
        return 'an ' + type;
      case 'boolean':
      case 'date':
      case 'regexp':
        return 'a ' + type;
      default:
        return type;
    }
  }

  // Returns class name of the object, if any.
  function getClassName(propValue) {
    if (!propValue.constructor || !propValue.constructor.name) {
      return ANONYMOUS;
    }
    return propValue.constructor.name;
  }

  ReactPropTypes.checkPropTypes = checkPropTypes;
  ReactPropTypes.resetWarningCache = checkPropTypes.resetWarningCache;
  ReactPropTypes.PropTypes = ReactPropTypes;

  return ReactPropTypes;
};


/***/ }),

/***/ "./node_modules/prop-types/index.js":
/*!******************************************!*\
  !*** ./node_modules/prop-types/index.js ***!
  \******************************************/
/***/ ((module, __unused_webpack_exports, __webpack_require__) => {

/**
 * Copyright (c) 2013-present, Facebook, Inc.
 *
 * This source code is licensed under the MIT license found in the
 * LICENSE file in the root directory of this source tree.
 */

if (true) {
  var ReactIs = __webpack_require__(/*! react-is */ "./node_modules/prop-types/node_modules/react-is/index.js");

  // By explicitly using `prop-types` you are opting into new development behavior.
  // http://fb.me/prop-types-in-prod
  var throwOnDirectAccess = true;
  module.exports = __webpack_require__(/*! ./factoryWithTypeCheckers */ "./node_modules/prop-types/factoryWithTypeCheckers.js")(ReactIs.isElement, throwOnDirectAccess);
} else // removed by dead control flow
{}


/***/ }),

/***/ "./node_modules/prop-types/lib/has.js":
/*!********************************************!*\
  !*** ./node_modules/prop-types/lib/has.js ***!
  \********************************************/
/***/ ((module) => {

module.exports = Function.call.bind(Object.prototype.hasOwnProperty);


/***/ }),

/***/ "./node_modules/prop-types/node_modules/react-is/cjs/react-is.development.js":
/*!***********************************************************************************!*\
  !*** ./node_modules/prop-types/node_modules/react-is/cjs/react-is.development.js ***!
  \***********************************************************************************/
/***/ ((__unused_webpack_module, exports) => {

"use strict";
/** @license React v16.13.1
 * react-is.development.js
 *
 * Copyright (c) Facebook, Inc. and its affiliates.
 *
 * This source code is licensed under the MIT license found in the
 * LICENSE file in the root directory of this source tree.
 */





if (true) {
  (function() {
'use strict';

// The Symbol used to tag the ReactElement-like types. If there is no native Symbol
// nor polyfill, then a plain number is used for performance.
var hasSymbol = typeof Symbol === 'function' && Symbol.for;
var REACT_ELEMENT_TYPE = hasSymbol ? Symbol.for('react.element') : 0xeac7;
var REACT_PORTAL_TYPE = hasSymbol ? Symbol.for('react.portal') : 0xeaca;
var REACT_FRAGMENT_TYPE = hasSymbol ? Symbol.for('react.fragment') : 0xeacb;
var REACT_STRICT_MODE_TYPE = hasSymbol ? Symbol.for('react.strict_mode') : 0xeacc;
var REACT_PROFILER_TYPE = hasSymbol ? Symbol.for('react.profiler') : 0xead2;
var REACT_PROVIDER_TYPE = hasSymbol ? Symbol.for('react.provider') : 0xeacd;
var REACT_CONTEXT_TYPE = hasSymbol ? Symbol.for('react.context') : 0xeace; // TODO: We don't use AsyncMode or ConcurrentMode anymore. They were temporary
// (unstable) APIs that have been removed. Can we remove the symbols?

var REACT_ASYNC_MODE_TYPE = hasSymbol ? Symbol.for('react.async_mode') : 0xeacf;
var REACT_CONCURRENT_MODE_TYPE = hasSymbol ? Symbol.for('react.concurrent_mode') : 0xeacf;
var REACT_FORWARD_REF_TYPE = hasSymbol ? Symbol.for('react.forward_ref') : 0xead0;
var REACT_SUSPENSE_TYPE = hasSymbol ? Symbol.for('react.suspense') : 0xead1;
var REACT_SUSPENSE_LIST_TYPE = hasSymbol ? Symbol.for('react.suspense_list') : 0xead8;
var REACT_MEMO_TYPE = hasSymbol ? Symbol.for('react.memo') : 0xead3;
var REACT_LAZY_TYPE = hasSymbol ? Symbol.for('react.lazy') : 0xead4;
var REACT_BLOCK_TYPE = hasSymbol ? Symbol.for('react.block') : 0xead9;
var REACT_FUNDAMENTAL_TYPE = hasSymbol ? Symbol.for('react.fundamental') : 0xead5;
var REACT_RESPONDER_TYPE = hasSymbol ? Symbol.for('react.responder') : 0xead6;
var REACT_SCOPE_TYPE = hasSymbol ? Symbol.for('react.scope') : 0xead7;

function isValidElementType(type) {
  return typeof type === 'string' || typeof type === 'function' || // Note: its typeof might be other than 'symbol' or 'number' if it's a polyfill.
  type === REACT_FRAGMENT_TYPE || type === REACT_CONCURRENT_MODE_TYPE || type === REACT_PROFILER_TYPE || type === REACT_STRICT_MODE_TYPE || type === REACT_SUSPENSE_TYPE || type === REACT_SUSPENSE_LIST_TYPE || typeof type === 'object' && type !== null && (type.$$typeof === REACT_LAZY_TYPE || type.$$typeof === REACT_MEMO_TYPE || type.$$typeof === REACT_PROVIDER_TYPE || type.$$typeof === REACT_CONTEXT_TYPE || type.$$typeof === REACT_FORWARD_REF_TYPE || type.$$typeof === REACT_FUNDAMENTAL_TYPE || type.$$typeof === REACT_RESPONDER_TYPE || type.$$typeof === REACT_SCOPE_TYPE || type.$$typeof === REACT_BLOCK_TYPE);
}

function typeOf(object) {
  if (typeof object === 'object' && object !== null) {
    var $$typeof = object.$$typeof;

    switch ($$typeof) {
      case REACT_ELEMENT_TYPE:
        var type = object.type;

        switch (type) {
          case REACT_ASYNC_MODE_TYPE:
          case REACT_CONCURRENT_MODE_TYPE:
          case REACT_FRAGMENT_TYPE:
          case REACT_PROFILER_TYPE:
          case REACT_STRICT_MODE_TYPE:
          case REACT_SUSPENSE_TYPE:
            return type;

          default:
            var $$typeofType = type && type.$$typeof;

            switch ($$typeofType) {
              case REACT_CONTEXT_TYPE:
              case REACT_FORWARD_REF_TYPE:
              case REACT_LAZY_TYPE:
              case REACT_MEMO_TYPE:
              case REACT_PROVIDER_TYPE:
                return $$typeofType;

              default:
                return $$typeof;
            }

        }

      case REACT_PORTAL_TYPE:
        return $$typeof;
    }
  }

  return undefined;
} // AsyncMode is deprecated along with isAsyncMode

var AsyncMode = REACT_ASYNC_MODE_TYPE;
var ConcurrentMode = REACT_CONCURRENT_MODE_TYPE;
var ContextConsumer = REACT_CONTEXT_TYPE;
var ContextProvider = REACT_PROVIDER_TYPE;
var Element = REACT_ELEMENT_TYPE;
var ForwardRef = REACT_FORWARD_REF_TYPE;
var Fragment = REACT_FRAGMENT_TYPE;
var Lazy = REACT_LAZY_TYPE;
var Memo = REACT_MEMO_TYPE;
var Portal = REACT_PORTAL_TYPE;
var Profiler = REACT_PROFILER_TYPE;
var StrictMode = REACT_STRICT_MODE_TYPE;
var Suspense = REACT_SUSPENSE_TYPE;
var hasWarnedAboutDeprecatedIsAsyncMode = false; // AsyncMode should be deprecated

function isAsyncMode(object) {
  {
    if (!hasWarnedAboutDeprecatedIsAsyncMode) {
      hasWarnedAboutDeprecatedIsAsyncMode = true; // Using console['warn'] to evade Babel and ESLint

      console['warn']('The ReactIs.isAsyncMode() alias has been deprecated, ' + 'and will be removed in React 17+. Update your code to use ' + 'ReactIs.isConcurrentMode() instead. It has the exact same API.');
    }
  }

  return isConcurrentMode(object) || typeOf(object) === REACT_ASYNC_MODE_TYPE;
}
function isConcurrentMode(object) {
  return typeOf(object) === REACT_CONCURRENT_MODE_TYPE;
}
function isContextConsumer(object) {
  return typeOf(object) === REACT_CONTEXT_TYPE;
}
function isContextProvider(object) {
  return typeOf(object) === REACT_PROVIDER_TYPE;
}
function isElement(object) {
  return typeof object === 'object' && object !== null && object.$$typeof === REACT_ELEMENT_TYPE;
}
function isForwardRef(object) {
  return typeOf(object) === REACT_FORWARD_REF_TYPE;
}
function isFragment(object) {
  return typeOf(object) === REACT_FRAGMENT_TYPE;
}
function isLazy(object) {
  return typeOf(object) === REACT_LAZY_TYPE;
}
function isMemo(object) {
  return typeOf(object) === REACT_MEMO_TYPE;
}
function isPortal(object) {
  return typeOf(object) === REACT_PORTAL_TYPE;
}
function isProfiler(object) {
  return typeOf(object) === REACT_PROFILER_TYPE;
}
function isStrictMode(object) {
  return typeOf(object) === REACT_STRICT_MODE_TYPE;
}
function isSuspense(object) {
  return typeOf(object) === REACT_SUSPENSE_TYPE;
}

exports.AsyncMode = AsyncMode;
exports.ConcurrentMode = ConcurrentMode;
exports.ContextConsumer = ContextConsumer;
exports.ContextProvider = ContextProvider;
exports.Element = Element;
exports.ForwardRef = ForwardRef;
exports.Fragment = Fragment;
exports.Lazy = Lazy;
exports.Memo = Memo;
exports.Portal = Portal;
exports.Profiler = Profiler;
exports.StrictMode = StrictMode;
exports.Suspense = Suspense;
exports.isAsyncMode = isAsyncMode;
exports.isConcurrentMode = isConcurrentMode;
exports.isContextConsumer = isContextConsumer;
exports.isContextProvider = isContextProvider;
exports.isElement = isElement;
exports.isForwardRef = isForwardRef;
exports.isFragment = isFragment;
exports.isLazy = isLazy;
exports.isMemo = isMemo;
exports.isPortal = isPortal;
exports.isProfiler = isProfiler;
exports.isStrictMode = isStrictMode;
exports.isSuspense = isSuspense;
exports.isValidElementType = isValidElementType;
exports.typeOf = typeOf;
  })();
}


/***/ }),

/***/ "./node_modules/prop-types/node_modules/react-is/index.js":
/*!****************************************************************!*\
  !*** ./node_modules/prop-types/node_modules/react-is/index.js ***!
  \****************************************************************/
/***/ ((module, __unused_webpack_exports, __webpack_require__) => {

"use strict";


if (false) // removed by dead control flow
{} else {
  module.exports = __webpack_require__(/*! ./cjs/react-is.development.js */ "./node_modules/prop-types/node_modules/react-is/cjs/react-is.development.js");
}


/***/ }),

/***/ "./src/admin/SettingsApp.js":
/*!**********************************!*\
  !*** ./src/admin/SettingsApp.js ***!
  \**********************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/components */ "@wordpress/components");
/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_components__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _wordpress_core_data__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @wordpress/core-data */ "@wordpress/core-data");
/* harmony import */ var _wordpress_core_data__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_wordpress_core_data__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var _wordpress_data__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @wordpress/data */ "@wordpress/data");
/* harmony import */ var _wordpress_data__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(_wordpress_data__WEBPACK_IMPORTED_MODULE_2__);
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! @wordpress/i18n */ "@wordpress/i18n");
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_3___default = /*#__PURE__*/__webpack_require__.n(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__);
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! @wordpress/element */ "@wordpress/element");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_4___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_4__);
/* harmony import */ var _components_HeroSettings__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! ./components/HeroSettings */ "./src/admin/components/HeroSettings.js");
/* harmony import */ var _components_CardSettings__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(/*! ./components/CardSettings */ "./src/admin/components/CardSettings.js");
/* harmony import */ var _components_FaqSettings__WEBPACK_IMPORTED_MODULE_7__ = __webpack_require__(/*! ./components/FaqSettings */ "./src/admin/components/FaqSettings.js");
/* harmony import */ var _components_GeneralSettings__WEBPACK_IMPORTED_MODULE_8__ = __webpack_require__(/*! ./components/GeneralSettings */ "./src/admin/components/GeneralSettings.js");
/* harmony import */ var _components_WhatsAppSettings__WEBPACK_IMPORTED_MODULE_9__ = __webpack_require__(/*! ./components/WhatsAppSettings */ "./src/admin/components/WhatsAppSettings.js");
/* harmony import */ var _components_LoadingSkeleton__WEBPACK_IMPORTED_MODULE_10__ = __webpack_require__(/*! ./components/LoadingSkeleton */ "./src/admin/components/LoadingSkeleton.js");
/* harmony import */ var _components_SuccessAnimation__WEBPACK_IMPORTED_MODULE_11__ = __webpack_require__(/*! ./components/SuccessAnimation */ "./src/admin/components/SuccessAnimation.js");
/* harmony import */ var _components_NavItem__WEBPACK_IMPORTED_MODULE_12__ = __webpack_require__(/*! ./components/NavItem */ "./src/admin/components/NavItem.js");
/* harmony import */ var _constants__WEBPACK_IMPORTED_MODULE_13__ = __webpack_require__(/*! ./constants */ "./src/admin/constants.js");
/* harmony import */ var _hooks_useKeyboardShortcut__WEBPACK_IMPORTED_MODULE_14__ = __webpack_require__(/*! ./hooks/useKeyboardShortcut */ "./src/admin/hooks/useKeyboardShortcut.js");
/* harmony import */ var _admin_css__WEBPACK_IMPORTED_MODULE_15__ = __webpack_require__(/*! ./admin.css */ "./src/admin/admin.css");
/* harmony import */ var react_jsx_runtime__WEBPACK_IMPORTED_MODULE_16__ = __webpack_require__(/*! react/jsx-runtime */ "react/jsx-runtime");
/* harmony import */ var react_jsx_runtime__WEBPACK_IMPORTED_MODULE_16___default = /*#__PURE__*/__webpack_require__.n(react_jsx_runtime__WEBPACK_IMPORTED_MODULE_16__);














// Import constants and hooks



// Import modern admin styles


const SettingsApp = () => {
  const [settings, setSettings] = (0,_wordpress_core_data__WEBPACK_IMPORTED_MODULE_1__.useEntityProp)('root', 'site', 'dynos_options');
  const {
    saveEditedEntityRecord
  } = (0,_wordpress_data__WEBPACK_IMPORTED_MODULE_2__.useDispatch)('core');
  const [isSaving, setIsSaving] = (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_4__.useState)(false);
  const [saveSuccess, setSaveSuccess] = (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_4__.useState)(false);
  const [notices, setNotices] = (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_4__.useState)([]);
  const [activeTab, setActiveTab] = (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_4__.useState)(_constants__WEBPACK_IMPORTED_MODULE_13__.TABS.GENERAL);
  const updateSettings = newSettings => {
    setSettings({
      ...settings,
      ...newSettings
    });
  };
  const saveSettings = async () => {
    setIsSaving(true);
    setSaveSuccess(false);
    try {
      await saveEditedEntityRecord('root', 'site', settings.id);
      setSaveSuccess(true);
      setNotices([...notices, {
        id: 'save-success',
        content: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)('Settings saved successfully.', 'dynamic-online-services'),
        status: 'success'
      }]);
    } catch (error) {
      setNotices([...notices, {
        id: 'save-error',
        content: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)('Error saving settings.', 'dynamic-online-services'),
        status: 'error'
      }]);
    } finally {
      setIsSaving(false);
    }
  };
  const removeNotice = id => {
    setNotices(notices.filter(notice => notice.id !== id));
  };

  // Keyboard shortcut: Cmd/Ctrl + S to save
  (0,_hooks_useKeyboardShortcut__WEBPACK_IMPORTED_MODULE_14__.useKeyboardShortcut)(_constants__WEBPACK_IMPORTED_MODULE_13__.KEYBOARD_SHORTCUTS.SAVE, () => {
    if (!isSaving) {
      saveSettings();
    }
  });
  if (!settings) {
    return /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_16__.jsx)(_components_LoadingSkeleton__WEBPACK_IMPORTED_MODULE_10__["default"], {});
  }
  const pluginSettings = settings || {};

  // Navigation Items
  const navItems = [{
    id: _constants__WEBPACK_IMPORTED_MODULE_13__.TABS.GENERAL,
    label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)('General', 'dynamic-online-services'),
    icon: 'admin-settings',
    component: _components_GeneralSettings__WEBPACK_IMPORTED_MODULE_8__["default"]
  }, {
    id: _constants__WEBPACK_IMPORTED_MODULE_13__.TABS.HERO,
    label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)('Hero Section', 'dynamic-online-services'),
    icon: 'cover-image',
    component: _components_HeroSettings__WEBPACK_IMPORTED_MODULE_5__["default"]
  }, {
    id: _constants__WEBPACK_IMPORTED_MODULE_13__.TABS.CARDS,
    label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)('Service Cards', 'dynamic-online-services'),
    icon: 'grid-view',
    component: _components_CardSettings__WEBPACK_IMPORTED_MODULE_6__["default"]
  }, {
    id: _constants__WEBPACK_IMPORTED_MODULE_13__.TABS.FAQS,
    label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)('FAQs', 'dynamic-online-services'),
    icon: 'format-chat',
    component: _components_FaqSettings__WEBPACK_IMPORTED_MODULE_7__["default"]
  }, {
    id: _constants__WEBPACK_IMPORTED_MODULE_13__.TABS.WHATSAPP,
    label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)('WhatsApp Widget', 'dynamic-online-services'),
    icon: 'whatsapp',
    component: _components_WhatsAppSettings__WEBPACK_IMPORTED_MODULE_9__["default"]
  }];
  const ActiveComponent = navItems.find(item => item.id === activeTab)?.component || _components_GeneralSettings__WEBPACK_IMPORTED_MODULE_8__["default"];
  return /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_16__.jsx)("div", {
    className: "dynos-settings-wrap",
    children: /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_16__.jsxs)("div", {
      className: "dynos-app-container",
      children: [/*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_16__.jsxs)("aside", {
        className: "dynos-sidebar",
        children: [/*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_16__.jsx)("div", {
          className: "dynos-sidebar-header",
          children: /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_16__.jsxs)("h1", {
            children: [/*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_16__.jsx)(_wordpress_components__WEBPACK_IMPORTED_MODULE_0__.Dashicon, {
              icon: "superhero-alt"
            }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_16__.jsx)("span", {
              children: "DynOS"
            })]
          })
        }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_16__.jsx)("nav", {
          className: "dynos-sidebar-nav",
          children: navItems.map(item => /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_16__.jsx)(_components_NavItem__WEBPACK_IMPORTED_MODULE_12__["default"], {
            id: item.id,
            label: item.label,
            icon: item.icon,
            activeTab: activeTab,
            setActiveTab: setActiveTab
          }, item.id))
        }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_16__.jsx)("div", {
          className: "dynos-sidebar-footer",
          children: /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_16__.jsxs)(_wordpress_components__WEBPACK_IMPORTED_MODULE_0__.Button, {
            className: `dynos-save-btn ${saveSuccess ? 'is-success' : ''}`,
            onClick: saveSettings,
            isBusy: isSaving,
            disabled: isSaving,
            "aria-live": "polite",
            children: [isSaving ? (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)('Saving…', 'dynamic-online-services') : (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)('Save Changes', 'dynamic-online-services'), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_16__.jsx)(_components_SuccessAnimation__WEBPACK_IMPORTED_MODULE_11__["default"], {
              show: saveSuccess,
              onComplete: () => setSaveSuccess(false)
            })]
          })
        })]
      }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_16__.jsxs)("main", {
        className: "dynos-main-content",
        children: [/*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_16__.jsx)(_wordpress_components__WEBPACK_IMPORTED_MODULE_0__.SnackbarList, {
          notices: notices,
          onRemove: removeNotice,
          className: "components-snackbar-list"
        }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_16__.jsxs)("div", {
          className: "dynos-content-header",
          children: [/*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_16__.jsx)("h2", {
            className: "dynos-section-title",
            children: navItems.find(i => i.id === activeTab)?.label
          }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_16__.jsx)("p", {
            className: "dynos-section-desc",
            children: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)('Manage settings for this section below.', 'dynamic-online-services')
          })]
        }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_16__.jsx)("div", {
          className: "dynos-panel-wrapper",
          children: /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_16__.jsx)(ActiveComponent, {
            settings: pluginSettings,
            onChange: updateSettings
          })
        })]
      })]
    })
  });
};
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (SettingsApp);

/***/ }),

/***/ "./src/admin/admin.css":
/*!*****************************!*\
  !*** ./src/admin/admin.css ***!
  \*****************************/
/***/ (() => {

throw new Error("Module build failed (from ./node_modules/mini-css-extract-plugin/dist/loader.js):\nHookWebpackError: Module build failed: Error: ENOENT: no such file or directory, open '/Users/syedaalin/Documents/dynamic-online-services/node_modules/css-loader/dist/runtime/sourceMaps.js'\n    at tryRunOrWebpackError (/Users/syedaalin/Documents/dynamic-online-services/node_modules/webpack/lib/HookWebpackError.js:85:9)\n    at __webpack_require_module__ (/Users/syedaalin/Documents/dynamic-online-services/node_modules/webpack/lib/Compilation.js:5564:12)\n    at __webpack_require__ (/Users/syedaalin/Documents/dynamic-online-services/node_modules/webpack/lib/Compilation.js:5511:18)\n    at Module.<anonymous> (/Users/syedaalin/Documents/dynamic-online-services/node_modules/css-loader/dist/cjs.js??ruleSet[1].rules[2].use[1]!/Users/syedaalin/Documents/dynamic-online-services/node_modules/postcss-loader/dist/cjs.js??ruleSet[1].rules[2].use[2]!/Users/syedaalin/Documents/dynamic-online-services/src/admin/admin.css:5:109)\n    at /Users/syedaalin/Documents/dynamic-online-services/node_modules/webpack/lib/javascript/JavascriptModulesPlugin.js:548:10\n    at Hook.eval [as call] (eval at create (/Users/syedaalin/Documents/dynamic-online-services/node_modules/tapable/lib/HookCodeFactory.js:19:10), <anonymous>:7:1)\n    at /Users/syedaalin/Documents/dynamic-online-services/node_modules/webpack/lib/Compilation.js:5566:39\n    at tryRunOrWebpackError (/Users/syedaalin/Documents/dynamic-online-services/node_modules/webpack/lib/HookWebpackError.js:80:7)\n    at __webpack_require_module__ (/Users/syedaalin/Documents/dynamic-online-services/node_modules/webpack/lib/Compilation.js:5564:12)\n    at __webpack_require__ (/Users/syedaalin/Documents/dynamic-online-services/node_modules/webpack/lib/Compilation.js:5511:18)\n    at /Users/syedaalin/Documents/dynamic-online-services/node_modules/webpack/lib/Compilation.js:5599:20\n    at symbolIterator (/Users/syedaalin/Documents/dynamic-online-services/node_modules/neo-async/async.js:3485:9)\n    at done (/Users/syedaalin/Documents/dynamic-online-services/node_modules/neo-async/async.js:3527:9)\n    at Hook.eval [as callAsync] (eval at create (/Users/syedaalin/Documents/dynamic-online-services/node_modules/tapable/lib/HookCodeFactory.js:31:10), <anonymous>:15:1)\n    at /Users/syedaalin/Documents/dynamic-online-services/node_modules/webpack/lib/Compilation.js:5486:43\n    at symbolIterator (/Users/syedaalin/Documents/dynamic-online-services/node_modules/neo-async/async.js:3482:9)\n    at timesSync (/Users/syedaalin/Documents/dynamic-online-services/node_modules/neo-async/async.js:2297:7)\n    at Object.eachLimit (/Users/syedaalin/Documents/dynamic-online-services/node_modules/neo-async/async.js:3463:5)\n    at /Users/syedaalin/Documents/dynamic-online-services/node_modules/webpack/lib/Compilation.js:5449:16\n    at symbolIterator (/Users/syedaalin/Documents/dynamic-online-services/node_modules/neo-async/async.js:3485:9)\n    at timesSync (/Users/syedaalin/Documents/dynamic-online-services/node_modules/neo-async/async.js:2297:7)\n    at Object.eachLimit (/Users/syedaalin/Documents/dynamic-online-services/node_modules/neo-async/async.js:3463:5)\n    at /Users/syedaalin/Documents/dynamic-online-services/node_modules/webpack/lib/Compilation.js:5417:15\n    at symbolIterator (/Users/syedaalin/Documents/dynamic-online-services/node_modules/neo-async/async.js:3485:9)\n    at done (/Users/syedaalin/Documents/dynamic-online-services/node_modules/neo-async/async.js:3527:9)\n    at /Users/syedaalin/Documents/dynamic-online-services/node_modules/webpack/lib/Compilation.js:5363:8\n    at /Users/syedaalin/Documents/dynamic-online-services/node_modules/webpack/lib/Compilation.js:3770:5\n    at /Users/syedaalin/Documents/dynamic-online-services/node_modules/webpack/lib/Cache.js:100:5\n    at Hook.eval [as callAsync] (eval at create (/Users/syedaalin/Documents/dynamic-online-services/node_modules/tapable/lib/HookCodeFactory.js:31:10), <anonymous>:16:1)\n    at Cache.get (/Users/syedaalin/Documents/dynamic-online-services/node_modules/webpack/lib/Cache.js:82:18)\n    at ItemCacheFacade.get (/Users/syedaalin/Documents/dynamic-online-services/node_modules/webpack/lib/CacheFacade.js:115:15)\n    at Compilation._codeGenerationModule (/Users/syedaalin/Documents/dynamic-online-services/node_modules/webpack/lib/Compilation.js:3734:9)\n    at codeGen (/Users/syedaalin/Documents/dynamic-online-services/node_modules/webpack/lib/Compilation.js:5351:11)\n    at symbolIterator (/Users/syedaalin/Documents/dynamic-online-services/node_modules/neo-async/async.js:3482:9)\n    at timesSync (/Users/syedaalin/Documents/dynamic-online-services/node_modules/neo-async/async.js:2297:7)\n    at Object.eachLimit (/Users/syedaalin/Documents/dynamic-online-services/node_modules/neo-async/async.js:3463:5)\n    at /Users/syedaalin/Documents/dynamic-online-services/node_modules/webpack/lib/Compilation.js:5381:14\n    at processQueue (/Users/syedaalin/Documents/dynamic-online-services/node_modules/webpack/lib/util/processAsyncTree.js:61:4)\n    at process.processTicksAndRejections (node:internal/process/task_queues:84:11)\n-- inner error --\nError: Module build failed: Error: ENOENT: no such file or directory, open '/Users/syedaalin/Documents/dynamic-online-services/node_modules/css-loader/dist/runtime/sourceMaps.js'\n    at Object.<anonymous> (/Users/syedaalin/Documents/dynamic-online-services/node_modules/css-loader/dist/runtime/sourceMaps.js:1:7)\n    at /Users/syedaalin/Documents/dynamic-online-services/node_modules/webpack/lib/javascript/JavascriptModulesPlugin.js:548:10\n    at Hook.eval [as call] (eval at create (/Users/syedaalin/Documents/dynamic-online-services/node_modules/tapable/lib/HookCodeFactory.js:19:10), <anonymous>:7:1)\n    at /Users/syedaalin/Documents/dynamic-online-services/node_modules/webpack/lib/Compilation.js:5566:39\n    at tryRunOrWebpackError (/Users/syedaalin/Documents/dynamic-online-services/node_modules/webpack/lib/HookWebpackError.js:80:7)\n    at __webpack_require_module__ (/Users/syedaalin/Documents/dynamic-online-services/node_modules/webpack/lib/Compilation.js:5564:12)\n    at __webpack_require__ (/Users/syedaalin/Documents/dynamic-online-services/node_modules/webpack/lib/Compilation.js:5511:18)\n    at Module.<anonymous> (/Users/syedaalin/Documents/dynamic-online-services/node_modules/css-loader/dist/cjs.js??ruleSet[1].rules[2].use[1]!/Users/syedaalin/Documents/dynamic-online-services/node_modules/postcss-loader/dist/cjs.js??ruleSet[1].rules[2].use[2]!/Users/syedaalin/Documents/dynamic-online-services/src/admin/admin.css:5:109)\n    at /Users/syedaalin/Documents/dynamic-online-services/node_modules/webpack/lib/javascript/JavascriptModulesPlugin.js:548:10\n    at Hook.eval [as call] (eval at create (/Users/syedaalin/Documents/dynamic-online-services/node_modules/tapable/lib/HookCodeFactory.js:19:10), <anonymous>:7:1)\n    at /Users/syedaalin/Documents/dynamic-online-services/node_modules/webpack/lib/Compilation.js:5566:39\n    at tryRunOrWebpackError (/Users/syedaalin/Documents/dynamic-online-services/node_modules/webpack/lib/HookWebpackError.js:80:7)\n    at __webpack_require_module__ (/Users/syedaalin/Documents/dynamic-online-services/node_modules/webpack/lib/Compilation.js:5564:12)\n    at __webpack_require__ (/Users/syedaalin/Documents/dynamic-online-services/node_modules/webpack/lib/Compilation.js:5511:18)\n    at /Users/syedaalin/Documents/dynamic-online-services/node_modules/webpack/lib/Compilation.js:5599:20\n    at symbolIterator (/Users/syedaalin/Documents/dynamic-online-services/node_modules/neo-async/async.js:3485:9)\n    at done (/Users/syedaalin/Documents/dynamic-online-services/node_modules/neo-async/async.js:3527:9)\n    at Hook.eval [as callAsync] (eval at create (/Users/syedaalin/Documents/dynamic-online-services/node_modules/tapable/lib/HookCodeFactory.js:31:10), <anonymous>:15:1)\n    at /Users/syedaalin/Documents/dynamic-online-services/node_modules/webpack/lib/Compilation.js:5486:43\n    at symbolIterator (/Users/syedaalin/Documents/dynamic-online-services/node_modules/neo-async/async.js:3482:9)\n    at timesSync (/Users/syedaalin/Documents/dynamic-online-services/node_modules/neo-async/async.js:2297:7)\n    at Object.eachLimit (/Users/syedaalin/Documents/dynamic-online-services/node_modules/neo-async/async.js:3463:5)\n    at /Users/syedaalin/Documents/dynamic-online-services/node_modules/webpack/lib/Compilation.js:5449:16\n    at symbolIterator (/Users/syedaalin/Documents/dynamic-online-services/node_modules/neo-async/async.js:3485:9)\n    at timesSync (/Users/syedaalin/Documents/dynamic-online-services/node_modules/neo-async/async.js:2297:7)\n    at Object.eachLimit (/Users/syedaalin/Documents/dynamic-online-services/node_modules/neo-async/async.js:3463:5)\n    at /Users/syedaalin/Documents/dynamic-online-services/node_modules/webpack/lib/Compilation.js:5417:15\n    at symbolIterator (/Users/syedaalin/Documents/dynamic-online-services/node_modules/neo-async/async.js:3485:9)\n    at done (/Users/syedaalin/Documents/dynamic-online-services/node_modules/neo-async/async.js:3527:9)\n    at /Users/syedaalin/Documents/dynamic-online-services/node_modules/webpack/lib/Compilation.js:5363:8\n    at /Users/syedaalin/Documents/dynamic-online-services/node_modules/webpack/lib/Compilation.js:3770:5\n    at /Users/syedaalin/Documents/dynamic-online-services/node_modules/webpack/lib/Cache.js:100:5\n    at Hook.eval [as callAsync] (eval at create (/Users/syedaalin/Documents/dynamic-online-services/node_modules/tapable/lib/HookCodeFactory.js:31:10), <anonymous>:16:1)\n    at Cache.get (/Users/syedaalin/Documents/dynamic-online-services/node_modules/webpack/lib/Cache.js:82:18)\n    at ItemCacheFacade.get (/Users/syedaalin/Documents/dynamic-online-services/node_modules/webpack/lib/CacheFacade.js:115:15)\n    at Compilation._codeGenerationModule (/Users/syedaalin/Documents/dynamic-online-services/node_modules/webpack/lib/Compilation.js:3734:9)\n    at codeGen (/Users/syedaalin/Documents/dynamic-online-services/node_modules/webpack/lib/Compilation.js:5351:11)\n    at symbolIterator (/Users/syedaalin/Documents/dynamic-online-services/node_modules/neo-async/async.js:3482:9)\n    at timesSync (/Users/syedaalin/Documents/dynamic-online-services/node_modules/neo-async/async.js:2297:7)\n    at Object.eachLimit (/Users/syedaalin/Documents/dynamic-online-services/node_modules/neo-async/async.js:3463:5)\n    at /Users/syedaalin/Documents/dynamic-online-services/node_modules/webpack/lib/Compilation.js:5381:14\n    at processQueue (/Users/syedaalin/Documents/dynamic-online-services/node_modules/webpack/lib/util/processAsyncTree.js:61:4)\n    at process.processTicksAndRejections (node:internal/process/task_queues:84:11)\n\nGenerated code for /Users/syedaalin/Documents/dynamic-online-services/node_modules/css-loader/dist/runtime/sourceMaps.js\n1 | throw new Error(\"Module build failed: Error: ENOENT: no such file or directory, open '/Users/syedaalin/Documents/dynamic-online-services/node_modules/css-loader/dist/runtime/sourceMaps.js'\");\n\nGenerated code for /Users/syedaalin/Documents/dynamic-online-services/node_modules/css-loader/dist/cjs.js??ruleSet[1].rules[2].use[1]!/Users/syedaalin/Documents/dynamic-online-services/node_modules/postcss-loader/dist/cjs.js??ruleSet[1].rules[2].use[2]!/Users/syedaalin/Documents/dynamic-online-services/src/admin/admin.css\n  1 | __webpack_require__.r(__webpack_exports__);\n  2 | /* harmony export */ __webpack_require__.d(__webpack_exports__, {\n  3 | /* harmony export */   \"default\": () => (__WEBPACK_DEFAULT_EXPORT__)\n  4 | /* harmony export */ });\n  5 | /* harmony import */ var _node_modules_css_loader_dist_runtime_sourceMaps_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../../node_modules/css-loader/dist/runtime/sourceMaps.js */ \"/Users/syedaalin/Documents/dynamic-online-services/node_modules/css-loader/dist/runtime/sourceMaps.js\");\n  6 | /* harmony import */ var _node_modules_css_loader_dist_runtime_sourceMaps_js__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_node_modules_css_loader_dist_runtime_sourceMaps_js__WEBPACK_IMPORTED_MODULE_0__);\n  7 | /* harmony import */ var _node_modules_css_loader_dist_runtime_api_js__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ../../node_modules/css-loader/dist/runtime/api.js */ \"/Users/syedaalin/Documents/dynamic-online-services/node_modules/css-loader/dist/runtime/api.js\");\n  8 | /* harmony import */ var _node_modules_css_loader_dist_runtime_api_js__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_node_modules_css_loader_dist_runtime_api_js__WEBPACK_IMPORTED_MODULE_1__);\n  9 | // Imports\n 10 | \n 11 | \n 12 | var ___CSS_LOADER_EXPORT___ = _node_modules_css_loader_dist_runtime_api_js__WEBPACK_IMPORTED_MODULE_1___default()((_node_modules_css_loader_dist_runtime_sourceMaps_js__WEBPACK_IMPORTED_MODULE_0___default()));\n 13 | // Module\n 14 | ___CSS_LOADER_EXPORT___.push([module.id, `/**\n 15 |  * Dynamic Online Services - Admin Settings Page Styles\n 16 |  * Modern 2025 UI Design System - Premium Edition\n 17 |  */\n 18 | \n 19 | /* ============================================\n 20 |    CSS CUSTOM PROPERTIES - Design Tokens\n 21 |    ============================================ */\n 22 | \n 23 | :root {\n 24 | \n 25 | \t/* Brand Colors - Deep & Elegant */\n 26 | \t--dynos-primary: #4f46e5;\n 27 | \n 28 | \t/* Indigo 600 */\n 29 | \t--dynos-primary-hover: #4338ca;\n 30 | \n 31 | \t/* Indigo 700 */\n 32 | \t--dynos-primary-light: #e0e7ff;\n 33 | \n 34 | \t/* Indigo 100 */\n 35 | \t--dynos-primary-bg: #eef2ff;\n 36 | \n 37 | \t/* Indigo 50 */\n 38 | \t--dynos-primary-soft: rgba(79, 70, 229, 0.1);\n 39 | \t--dynos-primary-dark: #3730a3;\n 40 | \n 41 | \t/* Indigo 800 */\n 42 | \n 43 | \t--dynos-accent: #e11d48;\n 44 | \n 45 | \t/* Rose 600 */\n 46 | \t--dynos-accent-hover: #be123c;\n 47 | \n 48 | \t/* Rose 700 */\n 49 | \n 50 | \t/* Neutral Scale - Cool Grays */\n 51 | \t--dynos-bg-app: #f8fafc;\n 52 | \n 53 | \t/* Slate 50 */\n 54 | \t--dynos-bg-surface: #fff;\n 55 | \n 56 | \t/* White */\n 57 | \t--dynos-bg-surface-alt: #f1f5f9;\n 58 | \n 59 | \t/* Slate 100 */\n 60 | \n 61 | \t--dynos-text-main: #0f172a;\n 62 | \n 63 | \t/* Slate 900 */\n 64 | \t--dynos-text-secondary: #475569;\n 65 | \n 66 | \t/* Slate 600 */\n 67 | \t--dynos-text-muted: #94a3b8;\n 68 | \n 69 | \t/* Slate 400 */\n 70 | \t--dynos-text-inverse: #fff;\n 71 | \n 72 | \t--dynos-border: #e2e8f0;\n 73 | \n 74 | \t/* Slate 200 */\n 75 | \t--dynos-border-focus: #6366f1;\n 76 | \n 77 | \t/* Indigo 500 */\n 78 | \n 79 | \t/* Status Colors */\n 80 | \t--dynos-success: #10b981;\n 81 | \t--dynos-warning: #f59e0b;\n 82 | \t--dynos-danger: #ef4444;\n 83 | \n 84 | \t/* Shadows */\n 85 | \t--dynos-shadow-sm: 0 1px 3px 0 rgba(0, 0, 0, 0.05), 0 1px 2px -1px rgba(0, 0, 0, 0.05);\n 86 | \t--dynos-shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -2px rgba(0, 0, 0, 0.05);\n 87 | \t--dynos-shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.05), 0 4px 6px -4px rgba(0, 0, 0, 0.05);\n 88 | \t--dynos-shadow-inner: inset 0 2px 4px 0 rgba(0, 0, 0, 0.03);\n 89 | \n 90 | \t/* Radius */\n 91 | \t--dynos-radius-sm: 0.375rem;\n 92 | \t--dynos-radius-md: 0.5rem;\n 93 | \t--dynos-radius-lg: 0.75rem;\n 94 | \t--dynos-radius-xl: 1rem;\n 95 | \t--dynos-radius-full: 9999px;\n 96 | \n 97 | \t/* Spacing */\n 98 | \t--dynos-space-1: 0.25rem;\n 99 | \t--dynos-space-2: 0.5rem;\n100 | \t--dynos-space-3: 0.75rem;\n101 | \t--dynos-space-4: 1rem;\n102 | \t--dynos-space-5: 1.25rem;\n103 | \t--dynos-space-6: 1.5rem;\n104 | \t--dynos-space-8: 2rem;\n105 | \t--dynos-space-10: 2.5rem;\n106 | \n107 | \t/* Typography */\n108 | \t--dynos-font-sans: \"Inter\", -apple-system, blinkmacsystemfont, \"Segoe UI\", roboto, \"Helvetica Neue\", arial, sans-serif;\n109 | \n110 | \t/* Transitions */\n111 | \t--dynos-ease: cubic-bezier(0.4, 0, 0.2, 1);\n112 | \t--dynos-duration: 200ms;\n113 | }\n114 | \n115 | /* Dark Mode Variables */\n116 | \n117 | @media (prefers-color-scheme: dark) {\n118 | \n119 | \t:root {\n120 | \t\t--dynos-bg-app: #0f172a;\n121 | \n122 | \t\t/* Slate 900 */\n123 | \t\t--dynos-bg-surface: #1e293b;\n124 | \n125 | \t\t/* Slate 800 */\n126 | \t\t--dynos-bg-surface-alt: #334155;\n127 | \n128 | \t\t/* Slate 700 */\n129 | \n130 | \t\t--dynos-text-main: #f8fafc;\n131 | \n132 | \t\t/* Slate 50 */\n133 | \t\t--dynos-text-secondary: #cbd5e1;\n134 | \n135 | \t\t/* Slate 300 */\n136 | \t\t--dynos-text-muted: #94a3b8;\n137 | \n138 | \t\t/* Slate 400 */\n139 | \n140 | \t\t--dynos-border: #334155;\n141 | \n142 | \t\t/* Slate 700 */\n143 | \t\t--dynos-shadow-sm: 0 1px 2px 0 rgba(0, 0, 0, 0.3);\n144 | \t\t--dynos-shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.3);\n145 | \t\t--dynos-shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.3);\n146 | \t\t--dynos-shadow-inner: inset 0 2px 4px 0 rgba(0, 0, 0, 0.2);\n147 | \t}\n148 | }\n149 | \n150 | /* ============================================\n151 |    GLOBAL RESET & BASE\n152 |    ============================================ */\n153 | \n154 | .dynos-settings-wrap {\n155 | \tmargin: 0 -20px 0 -20px;\n156 | \n157 | \t/* Counteract WP admin padding */\n158 | \tpadding: 0;\n159 | \tfont-family: var(--dynos-font-sans);\n160 | \tbackground-color: var(--dynos-bg-app);\n161 | \tmin-height: calc(100vh - 32px);\n162 | \tdisplay: flex;\n163 | \tflex-direction: column;\n164 | \tcolor: var(--dynos-text-main);\n165 | \t-webkit-font-smoothing: antialiased;\n166 | }\n167 | \n168 | .dynos-settings-wrap * {\n169 | \tbox-sizing: border-box;\n170 | }\n171 | \n172 | /* ============================================\n173 |    LAYOUT STRUCTURE\n174 |    ============================================ */\n175 | \n176 | .dynos-app-container {\n177 | \tdisplay: flex !important;\n178 | \tmin-height: 100vh;\n179 | \tmax-width: 1400px;\n180 | \tmargin: 0 auto;\n181 | \twidth: 100%;\n182 | \tflex-direction: row !important;\n183 | \talign-items: flex-start;\n184 | }\n185 | \n186 | /* Sidebar Navigation */\n187 | \n188 | .dynos-sidebar {\n189 | \twidth: 280px !important;\n190 | \tflex-shrink: 0;\n191 | \tmin-width: 280px;\n192 | \tbackground: var(--dynos-bg-surface);\n193 | \tborder-inline-end: 1px solid var(--dynos-border);\n194 | \tdisplay: flex;\n195 | \tflex-direction: column;\n196 | \tposition: sticky;\n197 | \ttop: 32px;\n198 | \n199 | \t/* WP Admin Bar height */\n200 | \theight: calc(100vh - 32px);\n201 | \tz-index: 10;\n202 | }\n203 | \n204 | @media (prefers-color-scheme: light) {\n205 | \n206 | \t.dynos-sidebar {\n207 | \t\tbackground: rgba(255, 255, 255, 0.95);\n208 | \t\tbackdrop-filter: blur(10px);\n209 | \t}\n210 | }\n211 | \n212 | .dynos-sidebar-header {\n213 | \tpadding: var(--dynos-space-6) var(--dynos-space-6);\n214 | \tborder-bottom: 1px solid var(--dynos-border);\n215 | \tbackground: var(--dynos-bg-surface);\n216 | \tposition: relative;\n217 | \toverflow: hidden;\n218 | }\n219 | \n220 | .dynos-sidebar-header h1 {\n221 | \tfont-size: 1.5rem;\n222 | \tfont-weight: 800;\n223 | \tmargin: 0;\n224 | \tpadding: 0;\n225 | \tcolor: var(--dynos-text-main);\n226 | \tdisplay: flex;\n227 | \talign-items: center;\n228 | \tgap: var(--dynos-space-3);\n229 | \tletter-spacing: -0.03em;\n230 | }\n231 | \n232 | .dynos-sidebar-header h1 .dashicon {\n233 | \tcolor: var(--dynos-primary);\n234 | \tfont-size: 1.75rem;\n235 | \theight: 1.75rem;\n236 | \twidth: 1.75rem;\n237 | \tfilter: drop-shadow(0 2px 4px rgba(99, 102, 241, 0.3));\n238 | }\n239 | \n240 | .dynos-sidebar-nav {\n241 | \tflex: 1;\n242 | \tpadding: var(--dynos-space-4);\n243 | \toverflow-y: auto;\n244 | }\n245 | \n246 | .dynos-sidebar-footer {\n247 | \tpadding: var(--dynos-space-6);\n248 | \tborder-top: 1px solid var(--dynos-border);\n249 | \tbackground: var(--dynos-bg-surface);\n250 | }\n251 | \n252 | /* Main Content Area */\n253 | \n254 | .dynos-main-content {\n255 | \tflex: 1;\n256 | \tpadding: var(--dynos-space-8) var(--dynos-space-10);\n257 | \tposition: relative;\n258 | \tmax-width: 1100px;\n259 | \tanimation: fadeInUp 0.4s var(--dynos-ease);\n260 | }\n261 | \n262 | .dynos-content-header {\n263 | \tmargin-bottom: var(--dynos-space-8);\n264 | \tborder-bottom: 2px solid var(--dynos-border);\n265 | \tpadding-bottom: var(--dynos-space-6);\n266 | }\n267 | \n268 | .dynos-section-title {\n269 | \tfont-size: 2.25rem;\n270 | \tfont-weight: 800;\n271 | \tcolor: var(--dynos-text-main);\n272 | \tmargin-bottom: var(--dynos-space-2);\n273 | \tletter-spacing: -0.025em;\n274 | \tline-height: 1.2;\n275 | }\n276 | \n277 | .dynos-section-desc {\n278 | \tfont-size: 1.125rem;\n279 | \tcolor: var(--dynos-text-secondary);\n280 | \tmargin: 0;\n281 | \tline-height: 1.6;\n282 | }\n283 | \n284 | /* ============================================\n285 |    COMPONENT STYLES\n286 |    ============================================ */\n287 | \n288 | /* Nav Items */\n289 | \n290 | .dynos-nav-item {\n291 | \tdisplay: flex;\n292 | \talign-items: center;\n293 | \twidth: 100%;\n294 | \tpadding: var(--dynos-space-3) var(--dynos-space-4);\n295 | \tmargin-bottom: var(--dynos-space-1);\n296 | \tborder: none;\n297 | \tborder-radius: var(--dynos-radius-md);\n298 | \tbackground: transparent;\n299 | \tcolor: var(--dynos-text-secondary);\n300 | \tfont-weight: 500;\n301 | \tfont-size: 0.95rem;\n302 | \tcursor: pointer;\n303 | \ttransition: all var(--dynos-duration) var(--dynos-ease);\n304 | \ttext-align: left;\n305 | \tgap: var(--dynos-space-3);\n306 | \t-webkit-appearance: none;\n307 | \t-moz-appearance: none;\n308 | \t     appearance: none;\n309 | \tposition: relative;\n310 | \toverflow: hidden;\n311 | }\n312 | \n313 | .dynos-nav-item:hover {\n314 | \tbackground-color: var(--dynos-bg-surface-alt);\n315 | \tcolor: var(--dynos-primary);\n316 | \ttransform: translateX(2px);\n317 | }\n318 | \n319 | /* RTL Support for hover animation */\n320 | \n321 | body.rtl .dynos-nav-item:hover {\n322 | \ttransform: translateX(-2px);\n323 | }\n324 | \n325 | .dynos-nav-item.active {\n326 | \tbackground-color: var(--dynos-primary-bg);\n327 | \tcolor: var(--dynos-primary);\n328 | \tfont-weight: 600;\n329 | \tbox-shadow: var(--dynos-shadow-sm);\n330 | }\n331 | \n332 | /* Dark mode active state adjustment */\n333 | \n334 | @media (prefers-color-scheme: dark) {\n335 | \n336 | \t.dynos-nav-item.active {\n337 | \t\tbackground-color: rgba(79, 70, 229, 0.2);\n338 | \t}\n339 | }\n340 | \n341 | .dynos-nav-item.active::before {\n342 | \tcontent: \"\";\n343 | \tposition: absolute;\n344 | \tinset-inline-start: 0;\n345 | \ttop: 50%;\n346 | \ttransform: translateY(-50%);\n347 | \theight: 1.5rem;\n348 | \twidth: 3px;\n349 | \tbackground-color: var(--dynos-primary);\n350 | \tborder-radius: 0 4px 4px 0;\n351 | }\n352 | \n353 | .dynos-nav-item .dashicon {\n354 | \tfont-size: 1.25rem;\n355 | \ttransition: color 0.2s;\n356 | }\n357 | \n358 | /* Save Button Styling */\n359 | \n360 | .dynos-save-btn {\n361 | \twidth: 100%;\n362 | \tdisplay: flex !important;\n363 | \tjustify-content: center;\n364 | \tpadding: var(--dynos-space-3) !important;\n365 | \tfont-size: 1rem !important;\n366 | \tfont-weight: 600 !important;\n367 | \tbackground: linear-gradient(135deg, var(--dynos-primary), var(--dynos-primary-hover)) !important;\n368 | \tcolor: white !important;\n369 | \tborder: none !important;\n370 | \tborder-radius: var(--dynos-radius-md) !important;\n371 | \tbox-shadow: var(--dynos-shadow-md), 0 0 0 0 rgba(99, 102, 241, 0.5);\n372 | \ttransition: all var(--dynos-duration) var(--dynos-ease) !important;\n373 | \tcursor: pointer;\n374 | \ttext-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);\n375 | \tposition: relative;\n376 | \toverflow: hidden;\n377 | }\n378 | \n379 | .dynos-save-btn:hover:not(:disabled) {\n380 | \tbackground: linear-gradient(135deg, var(--dynos-primary-hover), var(--dynos-primary)) !important;\n381 | \ttransform: translateY(-2px);\n382 | \tbox-shadow: var(--dynos-shadow-lg), 0 0 0 4px rgba(99, 102, 241, 0.2);\n383 | }\n384 | \n385 | .dynos-save-btn:disabled {\n386 | \topacity: 0.7;\n387 | \tcursor: not-allowed;\n388 | \ttransform: none;\n389 | }\n390 | \n391 | .dynos-save-btn.is-success {\n392 | \tanimation: successPulse 0.6s ease-in-out;\n393 | \tbackground: linear-gradient(135deg, var(--dynos-success), #059669) !important;\n394 | }\n395 | \n396 | /* Panel/Cards Styles */\n397 | \n398 | .components-panel__body {\n399 | \tbackground: var(--dynos-bg-surface);\n400 | \tborder: 1px solid var(--dynos-border);\n401 | \tborder-radius: var(--dynos-radius-lg);\n402 | \tbox-shadow: var(--dynos-shadow-sm);\n403 | \tmargin-bottom: var(--dynos-space-6);\n404 | \toverflow: visible;\n405 | \n406 | \t/* Changed from hidden to show potential tooltips/popovers */\n407 | \ttransition: all 0.3s var(--dynos-ease);\n408 | }\n409 | \n410 | .components-panel__body:hover {\n411 | \tbox-shadow: var(--dynos-shadow-md);\n412 | \ttransform: translateY(-2px);\n413 | }\n414 | \n415 | .components-panel__body-title {\n416 | \tbackground: transparent;\n417 | \tpadding: var(--dynos-space-4) var(--dynos-space-6);\n418 | \ttransition: background-color 0.2s;\n419 | }\n420 | \n421 | .components-panel__body-title:hover {\n422 | \tbackground: var(--dynos-bg-surface-alt);\n423 | }\n424 | \n425 | .components-panel__body-title button {\n426 | \tfont-size: 1.1rem;\n427 | \tfont-weight: 600;\n428 | \tcolor: var(--dynos-text-main);\n429 | \toutline: none;\n430 | }\n431 | \n432 | /* Form Controls */\n433 | \n434 | .dynos-option-control {\n435 | \tpadding: var(--dynos-space-6);\n436 | \tborder-bottom: 1px solid var(--dynos-border);\n437 | \ttransition: background-color 0.2s;\n438 | }\n439 | \n440 | .dynos-option-control:hover {\n441 | \tbackground-color: var(--dynos-bg-surface-alt);\n442 | }\n443 | \n444 | .dynos-option-control:last-child {\n445 | \tborder-bottom: none;\n446 | }\n447 | \n448 | /* Responsive Grid for Form Controls */\n449 | \n450 | @media (min-width: 960px) {\n451 | \n452 | \t.dynos-option-control {\n453 | \t\tdisplay: grid;\n454 | \t\tgrid-template-columns: 240px 1fr;\n455 | \t\tgap: var(--dynos-space-8);\n456 | \t\talign-items: start;\n457 | \t}\n458 | }\n459 | \n460 | .dynos-control-header {\n461 | \tmargin-bottom: var(--dynos-space-3);\n462 | }\n463 | \n464 | .dynos-control-label {\n465 | \tdisplay: block;\n466 | \tfont-weight: 600;\n467 | \tcolor: var(--dynos-text-main);\n468 | \tmargin-bottom: var(--dynos-space-1);\n469 | \tfont-size: 0.95rem;\n470 | }\n471 | \n472 | .dynos-control-help {\n473 | \tdisplay: block;\n474 | \tfont-size: 0.875rem;\n475 | \tcolor: var(--dynos-text-muted);\n476 | \tline-height: 1.5;\n477 | }\n478 | \n479 | /* Input Fields Styling */\n480 | \n481 | .components-text-control__input,\n482 | .components-select-control__input,\n483 | .components-textarea-control__input,\n484 | input[type=\"text\"],\n485 | input[type=\"number\"],\n486 | input[type=\"email\"],\n487 | textarea,\n488 | select {\n489 | \tborder: 1px solid var(--dynos-border) !important;\n490 | \tborder-radius: var(--dynos-radius-md) !important;\n491 | \tpadding: 10px 14px !important;\n492 | \tfont-size: 0.95rem !important;\n493 | \tcolor: var(--dynos-text-main) !important;\n494 | \tbackground-color: var(--dynos-bg-surface) !important;\n495 | \tbox-shadow: var(--dynos-shadow-inner) !important;\n496 | \ttransition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1) !important;\n497 | \tmin-height: 42px;\n498 | \tline-height: 1.5;\n499 | }\n500 | \n501 | .components-text-control__input:focus,\n502 | .components-select-control__input:focus,\n503 | .components-textarea-control__input:focus {\n504 | \tborder-color: var(--dynos-primary) !important;\n505 | \tbox-shadow: 0 0 0 3px var(--dynos-primary-light) !important;\n506 | \toutline: none;\n507 | }\n508 | \n509 | /* Enhanced Focus Visible */\n510 | \n511 | .components-text-control__input:focus-visible,\n512 | .components-select-control__input:focus-visible,\n513 | .components-textarea-control__input:focus-visible,\n514 | .dynos-nav-item:focus-visible,\n515 | button:focus-visible {\n516 | \toutline: 3px solid var(--dynos-primary);\n517 | \toutline-offset: 2px;\n518 | }\n519 | \n520 | /* Color Picker */\n521 | \n522 | .components-color-palette__item {\n523 | \tborder: 1px solid var(--dynos-border);\n524 | \ttransition: transform 0.2s;\n525 | }\n526 | \n527 | .components-color-palette__item:hover {\n528 | \ttransform: scale(1.1);\n529 | \tz-index: 2;\n530 | }\n531 | \n532 | /* Toggle Control */\n533 | \n534 | .components-form-toggle.is-checked .components-form-toggle__track {\n535 | \tbackground-color: var(--dynos-primary) !important;\n536 | }\n537 | \n538 | .components-form-toggle__track {\n539 | \tbackground-color: #cbd5e1 !important;\n540 | \tborder: none !important;\n541 | \ttransition: background-color 0.3s var(--dynos-ease) !important;\n542 | }\n543 | \n544 | @media (prefers-color-scheme: dark) {\n545 | \n546 | \t.components-form-toggle__track {\n547 | \t\tbackground-color: #475569 !important;\n548 | \t}\n549 | }\n550 | \n551 | .components-form-toggle__thumb {\n552 | \ttransition: transform 0.3s var(--dynos-ease) !important;\n553 | }\n554 | \n555 | /* Warning Box */\n556 | \n557 | .dynos-warning-box {\n558 | \tbackground-color: #fff1f2;\n559 | \tborder: 1px solid #fecdd3;\n560 | \tborder-radius: var(--dynos-radius-md);\n561 | \tpadding: var(--dynos-space-4);\n562 | }\n563 | \n564 | @media (prefers-color-scheme: dark) {\n565 | \n566 | \t.dynos-warning-box {\n567 | \t\tbackground-color: rgba(225, 29, 72, 0.1);\n568 | \t\tborder-color: rgba(225, 29, 72, 0.3);\n569 | \t}\n570 | }\n571 | \n572 | .dynos-warning-box p {\n573 | \tcolor: #9f1239;\n574 | \tfont-size: 0.9rem;\n575 | \tmargin: var(--dynos-space-2) 0 0 0;\n576 | \tdisplay: flex;\n577 | \talign-items: center;\n578 | \tgap: 0.5rem;\n579 | }\n580 | \n581 | @media (prefers-color-scheme: dark) {\n582 | \n583 | \t.dynos-warning-box p {\n584 | \t\tcolor: #fca5a5;\n585 | \t}\n586 | }\n587 | \n588 | /* Snackbar */\n589 | \n590 | .components-snackbar-list {\n591 | \tz-index: 100000;\n592 | \tbottom: 24px;\n593 | \tinset-inline-end: 24px;\n594 | }\n595 | \n596 | .components-snackbar {\n597 | \tborder-radius: var(--dynos-radius-md) !important;\n598 | \tbox-shadow: var(--dynos-shadow-lg) !important;\n599 | \tfont-family: var(--dynos-font-sans) !important;\n600 | \tbackground: var(--dynos-text-main) !important;\n601 | \tcolor: var(--dynos-text-inverse) !important;\n602 | }\n603 | \n604 | /* ============================================\n605 |    ANIMATIONS\n606 |    ============================================ */\n607 | \n608 | @keyframes fadeInUp {\n609 | \n610 | \tfrom {\n611 | \t\topacity: 0;\n612 | \t\ttransform: translateY(20px);\n613 | \t}\n614 | \n615 | \tto {\n616 | \t\topacity: 1;\n617 | \t\ttransform: translateY(0);\n618 | \t}\n619 | }\n620 | \n621 | @keyframes successPulse {\n622 | \n623 | \t0%,\n624 | \t100% {\n625 | \t\ttransform: scale(1);\n626 | \t\topacity: 1;\n627 | \t}\n628 | \n629 | \t50% {\n630 | \t\ttransform: scale(1.05);\n631 | \t\topacity: 0.9;\n632 | \t}\n633 | }\n634 | \n635 | @keyframes skeleton-pulse {\n636 | \n637 | \t0%,\n638 | \t100% {\n639 | \t\topacity: 1;\n640 | \t}\n641 | \n642 | \t50% {\n643 | \t\topacity: 0.5;\n644 | \t}\n645 | }\n646 | \n647 | @keyframes shimmer {\n648 | \n649 | \t0% {\n650 | \t\tbackground-position: -1000px 0;\n651 | \t}\n652 | \n653 | \t100% {\n654 | \t\tbackground-position: 1000px 0;\n655 | \t}\n656 | }\n657 | \n658 | /* Skeleton Loading */\n659 | \n660 | .dynos-skeleton {\n661 | \tbackground:\n662 | \t\tlinear-gradient(90deg, var(--dynos-bg-surface-alt) 0%, var(--dynos-border) 50%, var(--dynos-bg-surface-alt) 100%);\n663 | \tbackground-size: 200% 100%;\n664 | \tanimation: skeleton-pulse 1.5s ease-in-out infinite;\n665 | \tborder-radius: var(--dynos-radius-md);\n666 | }\n667 | \n668 | .dynos-skeleton-sidebar {\n669 | \twidth: 100%;\n670 | \theight: 48px;\n671 | \tmargin-bottom: var(--dynos-space-2);\n672 | }\n673 | \n674 | .dynos-skeleton-panel {\n675 | \twidth: 100%;\n676 | \theight: 200px;\n677 | \tmargin-bottom: var(--dynos-space-6);\n678 | }\n679 | \n680 | .dynos-skeleton-input {\n681 | \twidth: 100%;\n682 | \theight: 42px;\n683 | \tmargin-bottom: var(--dynos-space-4);\n684 | }\n685 | \n686 | /* ============================================\n687 |    RESPONSIVE DESIGN (Mobile First)\n688 |    ============================================ */\n689 | \n690 | /* Reduced Motion */\n691 | \n692 | @media (prefers-reduced-motion: reduce) {\n693 | \n694 | \t*,\n695 | \t*::before,\n696 | \t*::after {\n697 | \t\tanimation-duration: 0.01ms !important;\n698 | \t\tanimation-iteration-count: 1 !important;\n699 | \t\ttransition-duration: 0.01ms !important;\n700 | \t}\n701 | }\n702 | \n703 | /* Tablet & Mobile */\n704 | \n705 | @media (max-width: 960px) {\n706 | \n707 | \t.dynos-app-container {\n708 | \t\tflex-direction: column !important;\n709 | \t}\n710 | \n711 | \t.dynos-sidebar {\n712 | \t\twidth: 100% !important;\n713 | \t\theight: auto;\n714 | \t\tposition: sticky;\n715 | \t\ttop: 32px;\n716 | \t\tborder-inline-end: none;\n717 | \t\tborder-bottom: 1px solid var(--dynos-border);\n718 | \t\tbox-shadow: var(--dynos-shadow-sm);\n719 | \t}\n720 | \n721 | \t.dynos-sidebar-nav {\n722 | \t\tdisplay: flex;\n723 | \t\toverflow-x: auto;\n724 | \t\tpadding: var(--dynos-space-2);\n725 | \t\twhite-space: nowrap;\n726 | \t\tgap: var(--dynos-space-2);\n727 | \t}\n728 | \n729 | \t.dynos-nav-item {\n730 | \t\twidth: auto;\n731 | \t\tmargin: 0;\n732 | \t\tpadding: var(--dynos-space-2) var(--dynos-space-4);\n733 | \t\tbackground: var(--dynos-bg-surface-alt);\n734 | \t\tborder-radius: 20px;\n735 | \t}\n736 | \n737 | \t.dynos-nav-item.active {\n738 | \t\tbackground: var(--dynos-primary);\n739 | \t\tcolor: white;\n740 | \t}\n741 | \n742 | \t.dynos-nav-item.active::before {\n743 | \t\tdisplay: none;\n744 | \t}\n745 | \n746 | \t.dynos-nav-item .dashicon {\n747 | \t\tfont-size: 1rem;\n748 | \t}\n749 | \n750 | \t.dynos-nav-item.active .dashicon {\n751 | \t\tcolor: white;\n752 | \t}\n753 | \n754 | \t.dynos-main-content {\n755 | \t\tpadding: var(--dynos-space-4);\n756 | \t}\n757 | \n758 | \t.dynos-section-title {\n759 | \t\tfont-size: 1.75rem;\n760 | \t}\n761 | \n762 | \t.dynos-sidebar-footer {\n763 | \t\tposition: fixed;\n764 | \t\tbottom: 0;\n765 | \t\tinset-inline-start: 0;\n766 | \t\tinset-inline-end: 0;\n767 | \t\tborder-top: 1px solid var(--dynos-border);\n768 | \t\tbox-shadow: 0 -4px 6px -1px rgba(0, 0, 0, 0.05);\n769 | \t\tz-index: 20;\n770 | \t\tpadding: var(--dynos-space-4);\n771 | \t}\n772 | \n773 | \t.dynos-settings-wrap {\n774 | \t\tmargin-bottom: 80px;\n775 | \n776 | \t\t/* Space for fixed footer */\n777 | \t}\n778 | }\n779 | \n780 | @media (max-width: 600px) {\n781 | \n782 | \t.dynos-option-control {\n783 | \t\tdisplay: block;\n784 | \t}\n785 | \n786 | \t.dynos-control-header {\n787 | \t\tmargin-bottom: var(--dynos-space-3);\n788 | \t}\n789 | }\n790 | \n791 | /* --- Utility & Consistency Classes --- */\n792 | \n793 | .dynos-divider {\n794 | \theight: 1px;\n795 | \tbackground: var(--dynos-border);\n796 | \tmargin: var(--dynos-space-large) 0;\n797 | \tborder: none;\n798 | }\n799 | \n800 | .dynos-agent-item {\n801 | \tbackground: var(--dynos-bg-alt);\n802 | \tpadding: var(--dynos-space-medium);\n803 | \tmargin-bottom: var(--dynos-space-medium);\n804 | \tborder-radius: var(--dynos-radius);\n805 | \tborder: 1px solid var(--dynos-border);\n806 | }\n807 | \n808 | .dynos-agent-header {\n809 | \tdisplay: flex;\n810 | \tjustify-content: space-between;\n811 | \talign-items: center;\n812 | \tmargin-bottom: var(--dynos-space-small);\n813 | }\n814 | \n815 | .dynos-agent-header h4 {\n816 | \tmargin: 0;\n817 | \tfont-size: 1rem;\n818 | \tcolor: var(--dynos-text-dark);\n819 | }\n820 | \n821 | .dynos-color-picker-trigger {\n822 | \tdisplay: flex;\n823 | \talign-items: center;\n824 | \tgap: var(--dynos-space-small);\n825 | }\n826 | \n827 | .dynos-color-swatch {\n828 | \twidth: 36px;\n829 | \theight: 36px;\n830 | \tborder-radius: 50%;\n831 | \tborder: 2px solid var(--dynos-white);\n832 | \tbox-shadow: var(--dynos-shadow-sm);\n833 | \tcursor: pointer;\n834 | \ttransition: transform var(--dynos-transition-fast);\n835 | }\n836 | \n837 | .dynos-color-swatch:hover {\n838 | \ttransform: scale(1.1);\n839 | }\n840 | \n841 | .dynos-color-code {\n842 | \tfont-family: var(--dynos-font-mono);\n843 | \tbackground: var(--dynos-bg-alt);\n844 | \tpadding: 2px 8px;\n845 | \tborder-radius: 4px;\n846 | \tfont-size: 0.85rem;\n847 | \tcolor: var(--dynos-text-light);\n848 | }\n849 | \n850 | .dynos-info-box {\n851 | \tbackground: var(--dynos-bg-alt);\n852 | \tpadding: var(--dynos-space-medium);\n853 | \tborder-left: 4px solid var(--dynos-primary);\n854 | \tborder-radius: var(--dynos-radius);\n855 | \tmargin: var(--dynos-space-small) 0;\n856 | \tfont-size: 0.9rem;\n857 | }\n858 | \n859 | .dynos-warning-box {\n860 | \tbackground: rgba(239, 68, 68, 0.05);\n861 | \t/* Soft red */\n862 | \tpadding: var(--dynos-space-medium);\n863 | \tborder-left: 4px solid var(--dynos-error);\n864 | \tborder-radius: var(--dynos-radius);\n865 | \tmargin: var(--dynos-space-small) 0;\n866 | }`, \"\",{\"version\":3,\"sources\":[\"webpack://./src/admin/admin.css\"],\"names\":[],\"mappings\":\"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\",\"sourcesContent\":[\"/**\\n * Dynamic Online Services - Admin Settings Page Styles\\n * Modern 2025 UI Design System - Premium Edition\\n */\\n\\n/* ============================================\\n   CSS CUSTOM PROPERTIES - Design Tokens\\n   ============================================ */\\n\\n:root {\\n\\n\\t/* Brand Colors - Deep & Elegant */\\n\\t--dynos-primary: #4f46e5;\\n\\n\\t/* Indigo 600 */\\n\\t--dynos-primary-hover: #4338ca;\\n\\n\\t/* Indigo 700 */\\n\\t--dynos-primary-light: #e0e7ff;\\n\\n\\t/* Indigo 100 */\\n\\t--dynos-primary-bg: #eef2ff;\\n\\n\\t/* Indigo 50 */\\n\\t--dynos-primary-soft: rgba(79, 70, 229, 0.1);\\n\\t--dynos-primary-dark: #3730a3;\\n\\n\\t/* Indigo 800 */\\n\\n\\t--dynos-accent: #e11d48;\\n\\n\\t/* Rose 600 */\\n\\t--dynos-accent-hover: #be123c;\\n\\n\\t/* Rose 700 */\\n\\n\\t/* Neutral Scale - Cool Grays */\\n\\t--dynos-bg-app: #f8fafc;\\n\\n\\t/* Slate 50 */\\n\\t--dynos-bg-surface: #fff;\\n\\n\\t/* White */\\n\\t--dynos-bg-surface-alt: #f1f5f9;\\n\\n\\t/* Slate 100 */\\n\\n\\t--dynos-text-main: #0f172a;\\n\\n\\t/* Slate 900 */\\n\\t--dynos-text-secondary: #475569;\\n\\n\\t/* Slate 600 */\\n\\t--dynos-text-muted: #94a3b8;\\n\\n\\t/* Slate 400 */\\n\\t--dynos-text-inverse: #fff;\\n\\n\\t--dynos-border: #e2e8f0;\\n\\n\\t/* Slate 200 */\\n\\t--dynos-border-focus: #6366f1;\\n\\n\\t/* Indigo 500 */\\n\\n\\t/* Status Colors */\\n\\t--dynos-success: #10b981;\\n\\t--dynos-warning: #f59e0b;\\n\\t--dynos-danger: #ef4444;\\n\\n\\t/* Shadows */\\n\\t--dynos-shadow-sm: 0 1px 3px 0 rgba(0, 0, 0, 0.05), 0 1px 2px -1px rgba(0, 0, 0, 0.05);\\n\\t--dynos-shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -2px rgba(0, 0, 0, 0.05);\\n\\t--dynos-shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.05), 0 4px 6px -4px rgba(0, 0, 0, 0.05);\\n\\t--dynos-shadow-inner: inset 0 2px 4px 0 rgba(0, 0, 0, 0.03);\\n\\n\\t/* Radius */\\n\\t--dynos-radius-sm: 0.375rem;\\n\\t--dynos-radius-md: 0.5rem;\\n\\t--dynos-radius-lg: 0.75rem;\\n\\t--dynos-radius-xl: 1rem;\\n\\t--dynos-radius-full: 9999px;\\n\\n\\t/* Spacing */\\n\\t--dynos-space-1: 0.25rem;\\n\\t--dynos-space-2: 0.5rem;\\n\\t--dynos-space-3: 0.75rem;\\n\\t--dynos-space-4: 1rem;\\n\\t--dynos-space-5: 1.25rem;\\n\\t--dynos-space-6: 1.5rem;\\n\\t--dynos-space-8: 2rem;\\n\\t--dynos-space-10: 2.5rem;\\n\\n\\t/* Typography */\\n\\t--dynos-font-sans: \\\"Inter\\\", -apple-system, blinkmacsystemfont, \\\"Segoe UI\\\", roboto, \\\"Helvetica Neue\\\", arial, sans-serif;\\n\\n\\t/* Transitions */\\n\\t--dynos-ease: cubic-bezier(0.4, 0, 0.2, 1);\\n\\t--dynos-duration: 200ms;\\n}\\n\\n/* Dark Mode Variables */\\n@media (prefers-color-scheme: dark) {\\n\\n\\t:root {\\n\\t\\t--dynos-bg-app: #0f172a;\\n\\n\\t\\t/* Slate 900 */\\n\\t\\t--dynos-bg-surface: #1e293b;\\n\\n\\t\\t/* Slate 800 */\\n\\t\\t--dynos-bg-surface-alt: #334155;\\n\\n\\t\\t/* Slate 700 */\\n\\n\\t\\t--dynos-text-main: #f8fafc;\\n\\n\\t\\t/* Slate 50 */\\n\\t\\t--dynos-text-secondary: #cbd5e1;\\n\\n\\t\\t/* Slate 300 */\\n\\t\\t--dynos-text-muted: #94a3b8;\\n\\n\\t\\t/* Slate 400 */\\n\\n\\t\\t--dynos-border: #334155;\\n\\n\\t\\t/* Slate 700 */\\n\\t\\t--dynos-shadow-sm: 0 1px 2px 0 rgba(0, 0, 0, 0.3);\\n\\t\\t--dynos-shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.3);\\n\\t\\t--dynos-shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.3);\\n\\t\\t--dynos-shadow-inner: inset 0 2px 4px 0 rgba(0, 0, 0, 0.2);\\n\\t}\\n}\\n\\n/* ============================================\\n   GLOBAL RESET & BASE\\n   ============================================ */\\n\\n.dynos-settings-wrap {\\n\\tmargin: 0 -20px 0 -20px;\\n\\n\\t/* Counteract WP admin padding */\\n\\tpadding: 0;\\n\\tfont-family: var(--dynos-font-sans);\\n\\tbackground-color: var(--dynos-bg-app);\\n\\tmin-height: calc(100vh - 32px);\\n\\tdisplay: flex;\\n\\tflex-direction: column;\\n\\tcolor: var(--dynos-text-main);\\n\\t-webkit-font-smoothing: antialiased;\\n}\\n\\n.dynos-settings-wrap * {\\n\\tbox-sizing: border-box;\\n}\\n\\n/* ============================================\\n   LAYOUT STRUCTURE\\n   ============================================ */\\n\\n.dynos-app-container {\\n\\tdisplay: flex !important;\\n\\tmin-height: 100vh;\\n\\tmax-width: 1400px;\\n\\tmargin: 0 auto;\\n\\twidth: 100%;\\n\\tflex-direction: row !important;\\n\\talign-items: flex-start;\\n}\\n\\n/* Sidebar Navigation */\\n.dynos-sidebar {\\n\\twidth: 280px !important;\\n\\tflex-shrink: 0;\\n\\tmin-width: 280px;\\n\\tbackground: var(--dynos-bg-surface);\\n\\tborder-inline-end: 1px solid var(--dynos-border);\\n\\tdisplay: flex;\\n\\tflex-direction: column;\\n\\tposition: sticky;\\n\\ttop: 32px;\\n\\n\\t/* WP Admin Bar height */\\n\\theight: calc(100vh - 32px);\\n\\tz-index: 10;\\n}\\n\\n@media (prefers-color-scheme: light) {\\n\\n\\t.dynos-sidebar {\\n\\t\\tbackground: rgba(255, 255, 255, 0.95);\\n\\t\\t-webkit-backdrop-filter: blur(10px);\\n\\t\\tbackdrop-filter: blur(10px);\\n\\t}\\n}\\n\\n.dynos-sidebar-header {\\n\\tpadding: var(--dynos-space-6) var(--dynos-space-6);\\n\\tborder-bottom: 1px solid var(--dynos-border);\\n\\tbackground: var(--dynos-bg-surface);\\n\\tposition: relative;\\n\\toverflow: hidden;\\n}\\n\\n.dynos-sidebar-header h1 {\\n\\tfont-size: 1.5rem;\\n\\tfont-weight: 800;\\n\\tmargin: 0;\\n\\tpadding: 0;\\n\\tcolor: var(--dynos-text-main);\\n\\tdisplay: flex;\\n\\talign-items: center;\\n\\tgap: var(--dynos-space-3);\\n\\tletter-spacing: -0.03em;\\n}\\n\\n.dynos-sidebar-header h1 .dashicon {\\n\\tcolor: var(--dynos-primary);\\n\\tfont-size: 1.75rem;\\n\\theight: 1.75rem;\\n\\twidth: 1.75rem;\\n\\tfilter: drop-shadow(0 2px 4px rgba(99, 102, 241, 0.3));\\n}\\n\\n.dynos-sidebar-nav {\\n\\tflex: 1;\\n\\tpadding: var(--dynos-space-4);\\n\\toverflow-y: auto;\\n}\\n\\n.dynos-sidebar-footer {\\n\\tpadding: var(--dynos-space-6);\\n\\tborder-top: 1px solid var(--dynos-border);\\n\\tbackground: var(--dynos-bg-surface);\\n}\\n\\n/* Main Content Area */\\n.dynos-main-content {\\n\\tflex: 1;\\n\\tpadding: var(--dynos-space-8) var(--dynos-space-10);\\n\\tposition: relative;\\n\\tmax-width: 1100px;\\n\\tanimation: fadeInUp 0.4s var(--dynos-ease);\\n}\\n\\n.dynos-content-header {\\n\\tmargin-bottom: var(--dynos-space-8);\\n\\tborder-bottom: 2px solid var(--dynos-border);\\n\\tpadding-bottom: var(--dynos-space-6);\\n}\\n\\n.dynos-section-title {\\n\\tfont-size: 2.25rem;\\n\\tfont-weight: 800;\\n\\tcolor: var(--dynos-text-main);\\n\\tmargin-bottom: var(--dynos-space-2);\\n\\tletter-spacing: -0.025em;\\n\\tline-height: 1.2;\\n}\\n\\n.dynos-section-desc {\\n\\tfont-size: 1.125rem;\\n\\tcolor: var(--dynos-text-secondary);\\n\\tmargin: 0;\\n\\tline-height: 1.6;\\n}\\n\\n/* ============================================\\n   COMPONENT STYLES\\n   ============================================ */\\n\\n/* Nav Items */\\n.dynos-nav-item {\\n\\tdisplay: flex;\\n\\talign-items: center;\\n\\twidth: 100%;\\n\\tpadding: var(--dynos-space-3) var(--dynos-space-4);\\n\\tmargin-bottom: var(--dynos-space-1);\\n\\tborder: none;\\n\\tborder-radius: var(--dynos-radius-md);\\n\\tbackground: transparent;\\n\\tcolor: var(--dynos-text-secondary);\\n\\tfont-weight: 500;\\n\\tfont-size: 0.95rem;\\n\\tcursor: pointer;\\n\\ttransition: all var(--dynos-duration) var(--dynos-ease);\\n\\ttext-align: left;\\n\\tgap: var(--dynos-space-3);\\n\\t-webkit-appearance: none;\\n\\tappearance: none;\\n\\tposition: relative;\\n\\toverflow: hidden;\\n}\\n\\n.dynos-nav-item:hover {\\n\\tbackground-color: var(--dynos-bg-surface-alt);\\n\\tcolor: var(--dynos-primary);\\n\\ttransform: translateX(2px);\\n}\\n\\n/* RTL Support for hover animation */\\nbody.rtl .dynos-nav-item:hover {\\n\\ttransform: translateX(-2px);\\n}\\n\\n.dynos-nav-item.active {\\n\\tbackground-color: var(--dynos-primary-bg);\\n\\tcolor: var(--dynos-primary);\\n\\tfont-weight: 600;\\n\\tbox-shadow: var(--dynos-shadow-sm);\\n}\\n\\n/* Dark mode active state adjustment */\\n@media (prefers-color-scheme: dark) {\\n\\n\\t.dynos-nav-item.active {\\n\\t\\tbackground-color: rgba(79, 70, 229, 0.2);\\n\\t}\\n}\\n\\n.dynos-nav-item.active::before {\\n\\tcontent: \\\"\\\";\\n\\tposition: absolute;\\n\\tinset-inline-start: 0;\\n\\ttop: 50%;\\n\\ttransform: translateY(-50%);\\n\\theight: 1.5rem;\\n\\twidth: 3px;\\n\\tbackground-color: var(--dynos-primary);\\n\\tborder-radius: 0 4px 4px 0;\\n}\\n\\n.dynos-nav-item .dashicon {\\n\\tfont-size: 1.25rem;\\n\\ttransition: color 0.2s;\\n}\\n\\n/* Save Button Styling */\\n.dynos-save-btn {\\n\\twidth: 100%;\\n\\tdisplay: flex !important;\\n\\tjustify-content: center;\\n\\tpadding: var(--dynos-space-3) !important;\\n\\tfont-size: 1rem !important;\\n\\tfont-weight: 600 !important;\\n\\tbackground: linear-gradient(135deg, var(--dynos-primary), var(--dynos-primary-hover)) !important;\\n\\tcolor: white !important;\\n\\tborder: none !important;\\n\\tborder-radius: var(--dynos-radius-md) !important;\\n\\tbox-shadow: var(--dynos-shadow-md), 0 0 0 0 rgba(99, 102, 241, 0.5);\\n\\ttransition: all var(--dynos-duration) var(--dynos-ease) !important;\\n\\tcursor: pointer;\\n\\ttext-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);\\n\\tposition: relative;\\n\\toverflow: hidden;\\n}\\n\\n.dynos-save-btn:hover:not(:disabled) {\\n\\tbackground: linear-gradient(135deg, var(--dynos-primary-hover), var(--dynos-primary)) !important;\\n\\ttransform: translateY(-2px);\\n\\tbox-shadow: var(--dynos-shadow-lg), 0 0 0 4px rgba(99, 102, 241, 0.2);\\n}\\n\\n.dynos-save-btn:disabled {\\n\\topacity: 0.7;\\n\\tcursor: not-allowed;\\n\\ttransform: none;\\n}\\n\\n.dynos-save-btn.is-success {\\n\\tanimation: successPulse 0.6s ease-in-out;\\n\\tbackground: linear-gradient(135deg, var(--dynos-success), #059669) !important;\\n}\\n\\n/* Panel/Cards Styles */\\n.components-panel__body {\\n\\tbackground: var(--dynos-bg-surface);\\n\\tborder: 1px solid var(--dynos-border);\\n\\tborder-radius: var(--dynos-radius-lg);\\n\\tbox-shadow: var(--dynos-shadow-sm);\\n\\tmargin-bottom: var(--dynos-space-6);\\n\\toverflow: visible;\\n\\n\\t/* Changed from hidden to show potential tooltips/popovers */\\n\\ttransition: all 0.3s var(--dynos-ease);\\n}\\n\\n.components-panel__body:hover {\\n\\tbox-shadow: var(--dynos-shadow-md);\\n\\ttransform: translateY(-2px);\\n}\\n\\n.components-panel__body-title {\\n\\tbackground: transparent;\\n\\tpadding: var(--dynos-space-4) var(--dynos-space-6);\\n\\ttransition: background-color 0.2s;\\n}\\n\\n.components-panel__body-title:hover {\\n\\tbackground: var(--dynos-bg-surface-alt);\\n}\\n\\n.components-panel__body-title button {\\n\\tfont-size: 1.1rem;\\n\\tfont-weight: 600;\\n\\tcolor: var(--dynos-text-main);\\n\\toutline: none;\\n}\\n\\n/* Form Controls */\\n.dynos-option-control {\\n\\tpadding: var(--dynos-space-6);\\n\\tborder-bottom: 1px solid var(--dynos-border);\\n\\ttransition: background-color 0.2s;\\n}\\n\\n.dynos-option-control:hover {\\n\\tbackground-color: var(--dynos-bg-surface-alt);\\n}\\n\\n.dynos-option-control:last-child {\\n\\tborder-bottom: none;\\n}\\n\\n/* Responsive Grid for Form Controls */\\n@media (min-width: 960px) {\\n\\n\\t.dynos-option-control {\\n\\t\\tdisplay: grid;\\n\\t\\tgrid-template-columns: 240px 1fr;\\n\\t\\tgap: var(--dynos-space-8);\\n\\t\\talign-items: start;\\n\\t}\\n}\\n\\n.dynos-control-header {\\n\\tmargin-bottom: var(--dynos-space-3);\\n}\\n\\n.dynos-control-label {\\n\\tdisplay: block;\\n\\tfont-weight: 600;\\n\\tcolor: var(--dynos-text-main);\\n\\tmargin-bottom: var(--dynos-space-1);\\n\\tfont-size: 0.95rem;\\n}\\n\\n.dynos-control-help {\\n\\tdisplay: block;\\n\\tfont-size: 0.875rem;\\n\\tcolor: var(--dynos-text-muted);\\n\\tline-height: 1.5;\\n}\\n\\n/* Input Fields Styling */\\n.components-text-control__input,\\n.components-select-control__input,\\n.components-textarea-control__input,\\ninput[type=\\\"text\\\"],\\ninput[type=\\\"number\\\"],\\ninput[type=\\\"email\\\"],\\ntextarea,\\nselect {\\n\\tborder: 1px solid var(--dynos-border) !important;\\n\\tborder-radius: var(--dynos-radius-md) !important;\\n\\tpadding: 10px 14px !important;\\n\\tfont-size: 0.95rem !important;\\n\\tcolor: var(--dynos-text-main) !important;\\n\\tbackground-color: var(--dynos-bg-surface) !important;\\n\\tbox-shadow: var(--dynos-shadow-inner) !important;\\n\\ttransition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1) !important;\\n\\tmin-height: 42px;\\n\\tline-height: 1.5;\\n}\\n\\n.components-text-control__input:focus,\\n.components-select-control__input:focus,\\n.components-textarea-control__input:focus {\\n\\tborder-color: var(--dynos-primary) !important;\\n\\tbox-shadow: 0 0 0 3px var(--dynos-primary-light) !important;\\n\\toutline: none;\\n}\\n\\n/* Enhanced Focus Visible */\\n.components-text-control__input:focus-visible,\\n.components-select-control__input:focus-visible,\\n.components-textarea-control__input:focus-visible,\\n.dynos-nav-item:focus-visible,\\nbutton:focus-visible {\\n\\toutline: 3px solid var(--dynos-primary);\\n\\toutline-offset: 2px;\\n}\\n\\n/* Color Picker */\\n.components-color-palette__item {\\n\\tborder: 1px solid var(--dynos-border);\\n\\ttransition: transform 0.2s;\\n}\\n\\n.components-color-palette__item:hover {\\n\\ttransform: scale(1.1);\\n\\tz-index: 2;\\n}\\n\\n/* Toggle Control */\\n.components-form-toggle.is-checked .components-form-toggle__track {\\n\\tbackground-color: var(--dynos-primary) !important;\\n}\\n\\n.components-form-toggle__track {\\n\\tbackground-color: #cbd5e1 !important;\\n\\tborder: none !important;\\n\\ttransition: background-color 0.3s var(--dynos-ease) !important;\\n}\\n\\n@media (prefers-color-scheme: dark) {\\n\\n\\t.components-form-toggle__track {\\n\\t\\tbackground-color: #475569 !important;\\n\\t}\\n}\\n\\n.components-form-toggle__thumb {\\n\\ttransition: transform 0.3s var(--dynos-ease) !important;\\n}\\n\\n/* Warning Box */\\n.dynos-warning-box {\\n\\tbackground-color: #fff1f2;\\n\\tborder: 1px solid #fecdd3;\\n\\tborder-radius: var(--dynos-radius-md);\\n\\tpadding: var(--dynos-space-4);\\n}\\n\\n@media (prefers-color-scheme: dark) {\\n\\n\\t.dynos-warning-box {\\n\\t\\tbackground-color: rgba(225, 29, 72, 0.1);\\n\\t\\tborder-color: rgba(225, 29, 72, 0.3);\\n\\t}\\n}\\n\\n.dynos-warning-box p {\\n\\tcolor: #9f1239;\\n\\tfont-size: 0.9rem;\\n\\tmargin: var(--dynos-space-2) 0 0 0;\\n\\tdisplay: flex;\\n\\talign-items: center;\\n\\tgap: 0.5rem;\\n}\\n\\n@media (prefers-color-scheme: dark) {\\n\\n\\t.dynos-warning-box p {\\n\\t\\tcolor: #fca5a5;\\n\\t}\\n}\\n\\n/* Snackbar */\\n.components-snackbar-list {\\n\\tz-index: 100000;\\n\\tbottom: 24px;\\n\\tinset-inline-end: 24px;\\n}\\n\\n.components-snackbar {\\n\\tborder-radius: var(--dynos-radius-md) !important;\\n\\tbox-shadow: var(--dynos-shadow-lg) !important;\\n\\tfont-family: var(--dynos-font-sans) !important;\\n\\tbackground: var(--dynos-text-main) !important;\\n\\tcolor: var(--dynos-text-inverse) !important;\\n}\\n\\n/* ============================================\\n   ANIMATIONS\\n   ============================================ */\\n\\n@keyframes fadeInUp {\\n\\n\\tfrom {\\n\\t\\topacity: 0;\\n\\t\\ttransform: translateY(20px);\\n\\t}\\n\\n\\tto {\\n\\t\\topacity: 1;\\n\\t\\ttransform: translateY(0);\\n\\t}\\n}\\n\\n@keyframes successPulse {\\n\\n\\t0%,\\n\\t100% {\\n\\t\\ttransform: scale(1);\\n\\t\\topacity: 1;\\n\\t}\\n\\n\\t50% {\\n\\t\\ttransform: scale(1.05);\\n\\t\\topacity: 0.9;\\n\\t}\\n}\\n\\n@keyframes skeleton-pulse {\\n\\n\\t0%,\\n\\t100% {\\n\\t\\topacity: 1;\\n\\t}\\n\\n\\t50% {\\n\\t\\topacity: 0.5;\\n\\t}\\n}\\n\\n@keyframes shimmer {\\n\\n\\t0% {\\n\\t\\tbackground-position: -1000px 0;\\n\\t}\\n\\n\\t100% {\\n\\t\\tbackground-position: 1000px 0;\\n\\t}\\n}\\n\\n/* Skeleton Loading */\\n.dynos-skeleton {\\n\\tbackground:\\n\\t\\tlinear-gradient(90deg, var(--dynos-bg-surface-alt) 0%, var(--dynos-border) 50%, var(--dynos-bg-surface-alt) 100%);\\n\\tbackground-size: 200% 100%;\\n\\tanimation: skeleton-pulse 1.5s ease-in-out infinite;\\n\\tborder-radius: var(--dynos-radius-md);\\n}\\n\\n.dynos-skeleton-sidebar {\\n\\twidth: 100%;\\n\\theight: 48px;\\n\\tmargin-bottom: var(--dynos-space-2);\\n}\\n\\n.dynos-skeleton-panel {\\n\\twidth: 100%;\\n\\theight: 200px;\\n\\tmargin-bottom: var(--dynos-space-6);\\n}\\n\\n.dynos-skeleton-input {\\n\\twidth: 100%;\\n\\theight: 42px;\\n\\tmargin-bottom: var(--dynos-space-4);\\n}\\n\\n/* ============================================\\n   RESPONSIVE DESIGN (Mobile First)\\n   ============================================ */\\n\\n/* Reduced Motion */\\n@media (prefers-reduced-motion: reduce) {\\n\\n\\t*,\\n\\t*::before,\\n\\t*::after {\\n\\t\\tanimation-duration: 0.01ms !important;\\n\\t\\tanimation-iteration-count: 1 !important;\\n\\t\\ttransition-duration: 0.01ms !important;\\n\\t}\\n}\\n\\n/* Tablet & Mobile */\\n@media (max-width: 960px) {\\n\\n\\t.dynos-app-container {\\n\\t\\tflex-direction: column !important;\\n\\t}\\n\\n\\t.dynos-sidebar {\\n\\t\\twidth: 100% !important;\\n\\t\\theight: auto;\\n\\t\\tposition: sticky;\\n\\t\\ttop: 32px;\\n\\t\\tborder-inline-end: none;\\n\\t\\tborder-bottom: 1px solid var(--dynos-border);\\n\\t\\tbox-shadow: var(--dynos-shadow-sm);\\n\\t}\\n\\n\\t.dynos-sidebar-nav {\\n\\t\\tdisplay: flex;\\n\\t\\toverflow-x: auto;\\n\\t\\tpadding: var(--dynos-space-2);\\n\\t\\twhite-space: nowrap;\\n\\t\\tgap: var(--dynos-space-2);\\n\\t}\\n\\n\\t.dynos-nav-item {\\n\\t\\twidth: auto;\\n\\t\\tmargin: 0;\\n\\t\\tpadding: var(--dynos-space-2) var(--dynos-space-4);\\n\\t\\tbackground: var(--dynos-bg-surface-alt);\\n\\t\\tborder-radius: 20px;\\n\\t}\\n\\n\\t.dynos-nav-item.active {\\n\\t\\tbackground: var(--dynos-primary);\\n\\t\\tcolor: white;\\n\\t}\\n\\n\\t.dynos-nav-item.active::before {\\n\\t\\tdisplay: none;\\n\\t}\\n\\n\\t.dynos-nav-item .dashicon {\\n\\t\\tfont-size: 1rem;\\n\\t}\\n\\n\\t.dynos-nav-item.active .dashicon {\\n\\t\\tcolor: white;\\n\\t}\\n\\n\\t.dynos-main-content {\\n\\t\\tpadding: var(--dynos-space-4);\\n\\t}\\n\\n\\t.dynos-section-title {\\n\\t\\tfont-size: 1.75rem;\\n\\t}\\n\\n\\t.dynos-sidebar-footer {\\n\\t\\tposition: fixed;\\n\\t\\tbottom: 0;\\n\\t\\tinset-inline-start: 0;\\n\\t\\tinset-inline-end: 0;\\n\\t\\tborder-top: 1px solid var(--dynos-border);\\n\\t\\tbox-shadow: 0 -4px 6px -1px rgba(0, 0, 0, 0.05);\\n\\t\\tz-index: 20;\\n\\t\\tpadding: var(--dynos-space-4);\\n\\t}\\n\\n\\t.dynos-settings-wrap {\\n\\t\\tmargin-bottom: 80px;\\n\\n\\t\\t/* Space for fixed footer */\\n\\t}\\n}\\n\\n@media (max-width: 600px) {\\n\\n\\t.dynos-option-control {\\n\\t\\tdisplay: block;\\n\\t}\\n\\n\\t.dynos-control-header {\\n\\t\\tmargin-bottom: var(--dynos-space-3);\\n\\t}\\n}\\n\\n/* --- Utility & Consistency Classes --- */\\n\\n.dynos-divider {\\n\\theight: 1px;\\n\\tbackground: var(--dynos-border);\\n\\tmargin: var(--dynos-space-large) 0;\\n\\tborder: none;\\n}\\n\\n.dynos-agent-item {\\n\\tbackground: var(--dynos-bg-alt);\\n\\tpadding: var(--dynos-space-medium);\\n\\tmargin-bottom: var(--dynos-space-medium);\\n\\tborder-radius: var(--dynos-radius);\\n\\tborder: 1px solid var(--dynos-border);\\n}\\n\\n.dynos-agent-header {\\n\\tdisplay: flex;\\n\\tjustify-content: space-between;\\n\\talign-items: center;\\n\\tmargin-bottom: var(--dynos-space-small);\\n}\\n\\n.dynos-agent-header h4 {\\n\\tmargin: 0;\\n\\tfont-size: 1rem;\\n\\tcolor: var(--dynos-text-dark);\\n}\\n\\n.dynos-color-picker-trigger {\\n\\tdisplay: flex;\\n\\talign-items: center;\\n\\tgap: var(--dynos-space-small);\\n}\\n\\n.dynos-color-swatch {\\n\\twidth: 36px;\\n\\theight: 36px;\\n\\tborder-radius: 50%;\\n\\tborder: 2px solid var(--dynos-white);\\n\\tbox-shadow: var(--dynos-shadow-sm);\\n\\tcursor: pointer;\\n\\ttransition: transform var(--dynos-transition-fast);\\n}\\n\\n.dynos-color-swatch:hover {\\n\\ttransform: scale(1.1);\\n}\\n\\n.dynos-color-code {\\n\\tfont-family: var(--dynos-font-mono);\\n\\tbackground: var(--dynos-bg-alt);\\n\\tpadding: 2px 8px;\\n\\tborder-radius: 4px;\\n\\tfont-size: 0.85rem;\\n\\tcolor: var(--dynos-text-light);\\n}\\n\\n.dynos-info-box {\\n\\tbackground: var(--dynos-bg-alt);\\n\\tpadding: var(--dynos-space-medium);\\n\\tborder-left: 4px solid var(--dynos-primary);\\n\\tborder-radius: var(--dynos-radius);\\n\\tmargin: var(--dynos-space-small) 0;\\n\\tfont-size: 0.9rem;\\n}\\n\\n.dynos-warning-box {\\n\\tbackground: rgba(239, 68, 68, 0.05);\\n\\t/* Soft red */\\n\\tpadding: var(--dynos-space-medium);\\n\\tborder-left: 4px solid var(--dynos-error);\\n\\tborder-radius: var(--dynos-radius);\\n\\tmargin: var(--dynos-space-small) 0;\\n}\"],\"sourceRoot\":\"\"}]);\n867 | // Exports\n868 | /* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (___CSS_LOADER_EXPORT___);\n869 | ");

/***/ }),

/***/ "./src/admin/components/CardSettings.js":
/*!**********************************************!*\
  !*** ./src/admin/components/CardSettings.js ***!
  \**********************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/components */ "@wordpress/components");
/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_components__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @wordpress/i18n */ "@wordpress/i18n");
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var _OptionControl__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./OptionControl */ "./src/admin/components/OptionControl.js");
/* harmony import */ var react_jsx_runtime__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! react/jsx-runtime */ "react/jsx-runtime");
/* harmony import */ var react_jsx_runtime__WEBPACK_IMPORTED_MODULE_3___default = /*#__PURE__*/__webpack_require__.n(react_jsx_runtime__WEBPACK_IMPORTED_MODULE_3__);




const CardSettings = ({
  settings,
  onChange
}) => {
  return /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_3__.jsxs)("div", {
    className: "dynos-tab-content",
    children: [/*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_3__.jsx)("h2", {
      children: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('Service Cards Settings', 'dynamic-online-services')
    }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_3__.jsxs)(_wordpress_components__WEBPACK_IMPORTED_MODULE_0__.PanelBody, {
      title: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('Colors', 'dynamic-online-services'),
      initialOpen: true,
      children: [/*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_3__.jsx)(_OptionControl__WEBPACK_IMPORTED_MODULE_2__["default"], {
        label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('Card Background Color', 'dynamic-online-services'),
        help: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('The background color of the service card.', 'dynamic-online-services'),
        type: "color",
        optionKey: "card_bg_color",
        settings: settings,
        onChange: onChange
      }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_3__.jsx)(_OptionControl__WEBPACK_IMPORTED_MODULE_2__["default"], {
        label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('Card Title Color', 'dynamic-online-services'),
        help: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('Color of the card title text.', 'dynamic-online-services'),
        type: "color",
        optionKey: "card_title_color",
        settings: settings,
        onChange: onChange
      }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_3__.jsx)(_OptionControl__WEBPACK_IMPORTED_MODULE_2__["default"], {
        label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('Card Description Color', 'dynamic-online-services'),
        help: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('Color of the card description text.', 'dynamic-online-services'),
        type: "color",
        optionKey: "card_description_color",
        settings: settings,
        onChange: onChange
      })]
    }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_3__.jsxs)(_wordpress_components__WEBPACK_IMPORTED_MODULE_0__.PanelBody, {
      title: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('Typography', 'dynamic-online-services'),
      initialOpen: false,
      children: [/*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_3__.jsx)(_OptionControl__WEBPACK_IMPORTED_MODULE_2__["default"], {
        label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('Card Title Font Size', 'dynamic-online-services'),
        help: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('Font size for the card title.', 'dynamic-online-services'),
        type: "unit",
        optionKey: "card_title_font_size",
        settings: settings,
        onChange: onChange
      }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_3__.jsx)(_OptionControl__WEBPACK_IMPORTED_MODULE_2__["default"], {
        label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('Card Description Font Size', 'dynamic-online-services'),
        help: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('Font size for the card description text.', 'dynamic-online-services'),
        type: "unit",
        optionKey: "card_description_font_size",
        settings: settings,
        onChange: onChange
      })]
    }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_3__.jsxs)(_wordpress_components__WEBPACK_IMPORTED_MODULE_0__.PanelBody, {
      title: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('Dimensions & Spacing', 'dynamic-online-services'),
      initialOpen: false,
      children: [/*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_3__.jsx)(_OptionControl__WEBPACK_IMPORTED_MODULE_2__["default"], {
        label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('Card Border Radius', 'dynamic-online-services'),
        help: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('Rounding of the card corners.', 'dynamic-online-services'),
        type: "unit",
        optionKey: "card_border_radius",
        settings: settings,
        onChange: onChange
      }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_3__.jsx)(_OptionControl__WEBPACK_IMPORTED_MODULE_2__["default"], {
        label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('Card Height', 'dynamic-online-services'),
        help: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('Total height of the card element.', 'dynamic-online-services'),
        type: "unit",
        optionKey: "card_height",
        settings: settings,
        onChange: onChange
      }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_3__.jsx)(_OptionControl__WEBPACK_IMPORTED_MODULE_2__["default"], {
        label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('Card Content Padding', 'dynamic-online-services'),
        help: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('Space on the left and right of the content.', 'dynamic-online-services'),
        type: "unit",
        optionKey: "card_content_padding",
        settings: settings,
        onChange: onChange
      })]
    }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_3__.jsxs)(_wordpress_components__WEBPACK_IMPORTED_MODULE_0__.PanelBody, {
      title: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('Grid Layout', 'dynamic-online-services'),
      initialOpen: false,
      children: [/*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_3__.jsx)(_OptionControl__WEBPACK_IMPORTED_MODULE_2__["default"], {
        label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('Grid Gap', 'dynamic-online-services'),
        help: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('Horizontal space between cards in the grid.', 'dynamic-online-services'),
        type: "unit",
        optionKey: "grid_gap",
        settings: settings,
        onChange: onChange
      }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_3__.jsx)(_OptionControl__WEBPACK_IMPORTED_MODULE_2__["default"], {
        label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('Grid Row Gap', 'dynamic-online-services'),
        help: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('Vertical space between rows of cards.', 'dynamic-online-services'),
        type: "unit",
        optionKey: "grid_row_gap",
        settings: settings,
        onChange: onChange
      })]
    })]
  });
};
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (CardSettings);

/***/ }),

/***/ "./src/admin/components/FaqSettings.js":
/*!*********************************************!*\
  !*** ./src/admin/components/FaqSettings.js ***!
  \*********************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/components */ "@wordpress/components");
/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_components__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @wordpress/i18n */ "@wordpress/i18n");
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var _OptionControl__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./OptionControl */ "./src/admin/components/OptionControl.js");
/* harmony import */ var react_jsx_runtime__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! react/jsx-runtime */ "react/jsx-runtime");
/* harmony import */ var react_jsx_runtime__WEBPACK_IMPORTED_MODULE_3___default = /*#__PURE__*/__webpack_require__.n(react_jsx_runtime__WEBPACK_IMPORTED_MODULE_3__);




const FaqSettings = ({
  settings,
  onChange
}) => {
  return /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_3__.jsxs)("div", {
    className: "dynos-tab-content",
    children: [/*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_3__.jsx)("h2", {
      children: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('FAQ Accordion Settings', 'dynamic-online-services')
    }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_3__.jsxs)(_wordpress_components__WEBPACK_IMPORTED_MODULE_0__.PanelBody, {
      title: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('Colors', 'dynamic-online-services'),
      initialOpen: true,
      children: [/*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_3__.jsx)(_OptionControl__WEBPACK_IMPORTED_MODULE_2__["default"], {
        label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('FAQ Item Border Color', 'dynamic-online-services'),
        type: "color",
        optionKey: "faq_item_border_color",
        settings: settings,
        onChange: onChange
      }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_3__.jsx)(_OptionControl__WEBPACK_IMPORTED_MODULE_2__["default"], {
        label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('FAQ Question Background', 'dynamic-online-services'),
        type: "color",
        optionKey: "faq_question_bg_color",
        settings: settings,
        onChange: onChange
      }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_3__.jsx)(_OptionControl__WEBPACK_IMPORTED_MODULE_2__["default"], {
        label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('FAQ Answer Text Color', 'dynamic-online-services'),
        type: "color",
        optionKey: "faq_answer_text_color",
        settings: settings,
        onChange: onChange
      })]
    }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_3__.jsxs)(_wordpress_components__WEBPACK_IMPORTED_MODULE_0__.PanelBody, {
      title: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('Dimensions', 'dynamic-online-services'),
      initialOpen: false,
      children: [/*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_3__.jsx)(_OptionControl__WEBPACK_IMPORTED_MODULE_2__["default"], {
        label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('Border Radius', 'dynamic-online-services'),
        help: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('Rounding of the FAQ item corners.', 'dynamic-online-services'),
        optionKey: "faq_item_border_radius",
        settings: settings,
        onChange: onChange
      }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_3__.jsx)(_OptionControl__WEBPACK_IMPORTED_MODULE_2__["default"], {
        label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('Margin Bottom', 'dynamic-online-services'),
        help: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('Space between FAQ items.', 'dynamic-online-services'),
        optionKey: "faq_item_margin_bottom",
        settings: settings,
        onChange: onChange
      })]
    })]
  });
};
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (FaqSettings);

/***/ }),

/***/ "./src/admin/components/GeneralSettings.js":
/*!*************************************************!*\
  !*** ./src/admin/components/GeneralSettings.js ***!
  \*************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/components */ "@wordpress/components");
/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_components__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @wordpress/i18n */ "@wordpress/i18n");
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var _OptionControl__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./OptionControl */ "./src/admin/components/OptionControl.js");
/* harmony import */ var react_jsx_runtime__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! react/jsx-runtime */ "react/jsx-runtime");
/* harmony import */ var react_jsx_runtime__WEBPACK_IMPORTED_MODULE_3___default = /*#__PURE__*/__webpack_require__.n(react_jsx_runtime__WEBPACK_IMPORTED_MODULE_3__);




const GeneralSettings = ({
  settings,
  onChange
}) => {
  return /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_3__.jsxs)("div", {
    className: "dynos-tab-content",
    children: [/*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_3__.jsx)("h2", {
      children: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('General Settings', 'dynamic-online-services')
    }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_3__.jsxs)(_wordpress_components__WEBPACK_IMPORTED_MODULE_0__.PanelBody, {
      title: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('Post Type', 'dynamic-online-services'),
      initialOpen: true,
      children: [/*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_3__.jsx)(_OptionControl__WEBPACK_IMPORTED_MODULE_2__["default"], {
        label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('Service Post Type Slug', 'dynamic-online-services'),
        help: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('URL slug for the service post type. Change requires flushing rewrite rules.', 'dynamic-online-services'),
        optionKey: "service_post_type_slug",
        settings: settings,
        onChange: onChange
      }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_3__.jsx)(_OptionControl__WEBPACK_IMPORTED_MODULE_2__["default"], {
        label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('Service Taxonomy Slug', 'dynamic-online-services'),
        help: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('URL slug for the service category taxonomy.', 'dynamic-online-services'),
        optionKey: "service_taxonomy_slug",
        settings: settings,
        onChange: onChange
      })]
    }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_3__.jsx)(_wordpress_components__WEBPACK_IMPORTED_MODULE_0__.PanelBody, {
      title: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('Uninstall', 'dynamic-online-services'),
      initialOpen: false,
      children: /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_3__.jsxs)("div", {
        className: "dynos-warning-box",
        children: [/*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_3__.jsx)(_OptionControl__WEBPACK_IMPORTED_MODULE_2__["default"], {
          label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('Delete Data on Uninstall', 'dynamic-online-services'),
          help: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('Remove all data when plugin is deleted.', 'dynamic-online-services'),
          type: "toggle",
          optionKey: "delete_data_on_uninstall",
          settings: settings,
          onChange: onChange
        }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_3__.jsx)("p", {
          children: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('⚠️ Warning: Enabling this will permanently delete all services and settings when you uninstall the plugin.', 'dynamic-online-services')
        })]
      })
    })]
  });
};
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (GeneralSettings);

/***/ }),

/***/ "./src/admin/components/HeroSettings.js":
/*!**********************************************!*\
  !*** ./src/admin/components/HeroSettings.js ***!
  \**********************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/components */ "@wordpress/components");
/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_components__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @wordpress/i18n */ "@wordpress/i18n");
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var _OptionControl__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./OptionControl */ "./src/admin/components/OptionControl.js");
/* harmony import */ var react_jsx_runtime__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! react/jsx-runtime */ "react/jsx-runtime");
/* harmony import */ var react_jsx_runtime__WEBPACK_IMPORTED_MODULE_3___default = /*#__PURE__*/__webpack_require__.n(react_jsx_runtime__WEBPACK_IMPORTED_MODULE_3__);




const HeroSettings = ({
  settings,
  onChange
}) => {
  return /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_3__.jsxs)("div", {
    className: "dynos-tab-content",
    children: [/*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_3__.jsx)("h2", {
      children: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('Hero Section Settings', 'dynamic-online-services')
    }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_3__.jsxs)(_wordpress_components__WEBPACK_IMPORTED_MODULE_0__.PanelBody, {
      title: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('Colors & Overlay', 'dynamic-online-services'),
      initialOpen: true,
      children: [/*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_3__.jsx)(_OptionControl__WEBPACK_IMPORTED_MODULE_2__["default"], {
        label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('Hero Title Color', 'dynamic-online-services'),
        help: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('Color of the main hero title.', 'dynamic-online-services'),
        type: "color",
        optionKey: "hero_title_color",
        settings: settings,
        onChange: onChange
      }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_3__.jsx)(_OptionControl__WEBPACK_IMPORTED_MODULE_2__["default"], {
        label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('Hero Description Color', 'dynamic-online-services'),
        help: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('Color of the hero description text.', 'dynamic-online-services'),
        type: "color",
        optionKey: "hero_description_color",
        settings: settings,
        onChange: onChange
      }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_3__.jsx)(_OptionControl__WEBPACK_IMPORTED_MODULE_2__["default"], {
        label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('Hero Overlay Color', 'dynamic-online-services'),
        help: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('This color will be semi-transparent over the background image.', 'dynamic-online-services'),
        type: "color",
        optionKey: "hero_overlay_color",
        settings: settings,
        onChange: onChange
      }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_3__.jsx)(_OptionControl__WEBPACK_IMPORTED_MODULE_2__["default"], {
        label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('Hero Overlay Opacity', 'dynamic-online-services'),
        help: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('Overlay opacity from 0 (transparent) to 1 (opaque).', 'dynamic-online-services'),
        type: "range",
        optionKey: "hero_overlay_opacity",
        settings: settings,
        onChange: onChange
      })]
    }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_3__.jsxs)(_wordpress_components__WEBPACK_IMPORTED_MODULE_0__.PanelBody, {
      title: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('Dimensions', 'dynamic-online-services'),
      initialOpen: false,
      children: [/*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_3__.jsx)(_OptionControl__WEBPACK_IMPORTED_MODULE_2__["default"], {
        label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('Hero Height (Desktop)', 'dynamic-online-services'),
        help: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('Hero section height for desktop. Supports px, vh, %. Example: 60vh', 'dynamic-online-services'),
        optionKey: "hero_height",
        settings: settings,
        onChange: onChange
      }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_3__.jsx)(_OptionControl__WEBPACK_IMPORTED_MODULE_2__["default"], {
        label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('Hero Height (Tablet)', 'dynamic-online-services'),
        help: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('Hero section height for tablets. Supports px, vh, %. Example: 50vh', 'dynamic-online-services'),
        optionKey: "hero_height_tablet",
        settings: settings,
        onChange: onChange
      }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_3__.jsx)(_OptionControl__WEBPACK_IMPORTED_MODULE_2__["default"], {
        label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('Hero Height (Mobile)', 'dynamic-online-services'),
        help: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('Hero section height for mobile. Supports px, vh, %. Example: 50vh', 'dynamic-online-services'),
        optionKey: "hero_height_mobile",
        settings: settings,
        onChange: onChange
      })]
    }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_3__.jsxs)(_wordpress_components__WEBPACK_IMPORTED_MODULE_0__.PanelBody, {
      title: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('Typography', 'dynamic-online-services'),
      initialOpen: false,
      children: [/*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_3__.jsx)(_OptionControl__WEBPACK_IMPORTED_MODULE_2__["default"], {
        label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('Google Font', 'dynamic-online-services'),
        help: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('Choose a Google Font for hero titles.', 'dynamic-online-services'),
        type: "select",
        optionKey: "hero_google_font",
        options: [{
          label: 'Default (sans-serif)',
          value: 'sans-serif'
        }, {
          label: 'Inter',
          value: 'Inter'
        }, {
          label: 'Roboto',
          value: 'Roboto'
        }, {
          label: 'Open Sans',
          value: 'Open Sans'
        }, {
          label: 'Montserrat',
          value: 'Montserrat'
        }, {
          label: 'Poppins',
          value: 'Poppins'
        }, {
          label: 'Playfair Display',
          value: 'Playfair Display'
        }, {
          label: 'Lato',
          value: 'Lato'
        }, {
          label: 'Raleway',
          value: 'Raleway'
        }, {
          label: 'Merriweather',
          value: 'Merriweather'
        }],
        settings: settings,
        onChange: onChange
      }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_3__.jsx)(_OptionControl__WEBPACK_IMPORTED_MODULE_2__["default"], {
        label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('Font Weight', 'dynamic-online-services'),
        help: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('Font weights to load (comma-separated, e.g., 400,700).', 'dynamic-online-services'),
        type: "text",
        optionKey: "hero_font_weight",
        settings: settings,
        onChange: onChange
      }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_3__.jsx)("div", {
        className: "dynos-divider"
      }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_3__.jsx)("h3", {
        children: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('Title Font Sizes', 'dynamic-online-services')
      }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_3__.jsx)(_OptionControl__WEBPACK_IMPORTED_MODULE_2__["default"], {
        label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('Title Font Size (Desktop)', 'dynamic-online-services'),
        help: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('Font size for the hero title on desktop. Example: 3rem', 'dynamic-online-services'),
        optionKey: "hero_title_font_size",
        settings: settings,
        onChange: onChange
      }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_3__.jsx)(_OptionControl__WEBPACK_IMPORTED_MODULE_2__["default"], {
        label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('Title Font Size (Tablet)', 'dynamic-online-services'),
        help: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('Font size for the hero title on tablets. Example: 2.5rem', 'dynamic-online-services'),
        optionKey: "hero_title_font_size_tablet",
        settings: settings,
        onChange: onChange
      }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_3__.jsx)(_OptionControl__WEBPACK_IMPORTED_MODULE_2__["default"], {
        label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('Title Font Size (Mobile)', 'dynamic-online-services'),
        help: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('Font size for the hero title on mobile. Example: 2rem', 'dynamic-online-services'),
        optionKey: "hero_title_font_size_mobile",
        settings: settings,
        onChange: onChange
      }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_3__.jsx)("div", {
        className: "dynos-divider"
      }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_3__.jsx)("h3", {
        children: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('Description Font Sizes', 'dynamic-online-services')
      }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_3__.jsx)(_OptionControl__WEBPACK_IMPORTED_MODULE_2__["default"], {
        label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('Description Font Size (Desktop)', 'dynamic-online-services'),
        help: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('Font size for the hero description on desktop. Example: 1.25rem', 'dynamic-online-services'),
        optionKey: "hero_description_font_size",
        settings: settings,
        onChange: onChange
      }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_3__.jsx)(_OptionControl__WEBPACK_IMPORTED_MODULE_2__["default"], {
        label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('Description Font Size (Tablet)', 'dynamic-online-services'),
        help: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('Font size for the hero description on tablets. Example: 1rem', 'dynamic-online-services'),
        optionKey: "hero_description_font_size_tablet",
        settings: settings,
        onChange: onChange
      }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_3__.jsx)(_OptionControl__WEBPACK_IMPORTED_MODULE_2__["default"], {
        label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('Description Font Size (Mobile)', 'dynamic-online-services'),
        help: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('Font size for the hero description on mobile. Example: 0.9rem', 'dynamic-online-services'),
        optionKey: "hero_description_font_size_mobile",
        settings: settings,
        onChange: onChange
      })]
    }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_3__.jsxs)(_wordpress_components__WEBPACK_IMPORTED_MODULE_0__.PanelBody, {
      title: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('Layout & Alignment', 'dynamic-online-services'),
      initialOpen: false,
      children: [/*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_3__.jsx)(_OptionControl__WEBPACK_IMPORTED_MODULE_2__["default"], {
        label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('Content Padding', 'dynamic-online-services'),
        help: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('Padding inside the hero content box. Example: 20px', 'dynamic-online-services'),
        optionKey: "hero_content_padding",
        settings: settings,
        onChange: onChange
      }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_3__.jsx)(_OptionControl__WEBPACK_IMPORTED_MODULE_2__["default"], {
        label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('Border Radius', 'dynamic-online-services'),
        help: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('Rounded corners radius for content box. Example: 10px', 'dynamic-online-services'),
        optionKey: "hero_border_radius",
        settings: settings,
        onChange: onChange
      }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_3__.jsx)(_OptionControl__WEBPACK_IMPORTED_MODULE_2__["default"], {
        label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('Margin Bottom', 'dynamic-online-services'),
        help: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('Space below the hero section. Example: 30px', 'dynamic-online-services'),
        optionKey: "hero_margin_bottom",
        settings: settings,
        onChange: onChange
      }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_3__.jsx)(_OptionControl__WEBPACK_IMPORTED_MODULE_2__["default"], {
        label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('Content Max Width', 'dynamic-online-services'),
        help: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('Maximum width of content box. Example: 90%, 800px', 'dynamic-online-services'),
        optionKey: "hero_content_max_width",
        settings: settings,
        onChange: onChange
      }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_3__.jsx)(_OptionControl__WEBPACK_IMPORTED_MODULE_2__["default"], {
        label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('Text Alignment', 'dynamic-online-services'),
        help: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('Horizontal text alignment inside the hero.', 'dynamic-online-services'),
        type: "select",
        optionKey: "hero_text_alignment",
        options: [{
          label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('Left', 'dynamic-online-services'),
          value: 'left'
        }, {
          label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('Center', 'dynamic-online-services'),
          value: 'center'
        }, {
          label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('Right', 'dynamic-online-services'),
          value: 'right'
        }],
        settings: settings,
        onChange: onChange
      }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_3__.jsx)(_OptionControl__WEBPACK_IMPORTED_MODULE_2__["default"], {
        label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('Vertical Alignment', 'dynamic-online-services'),
        help: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('Vertical position of content inside the hero.', 'dynamic-online-services'),
        type: "select",
        optionKey: "hero_vertical_alignment",
        options: [{
          label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('Top', 'dynamic-online-services'),
          value: 'top'
        }, {
          label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('Center', 'dynamic-online-services'),
          value: 'center'
        }, {
          label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('Bottom', 'dynamic-online-services'),
          value: 'bottom'
        }],
        settings: settings,
        onChange: onChange
      })]
    }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_3__.jsxs)(_wordpress_components__WEBPACK_IMPORTED_MODULE_0__.PanelBody, {
      title: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('CTA Button', 'dynamic-online-services'),
      initialOpen: false,
      children: [/*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_3__.jsx)(_OptionControl__WEBPACK_IMPORTED_MODULE_2__["default"], {
        label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('Enable CTA Button', 'dynamic-online-services'),
        help: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('Show a call-to-action button in the hero.', 'dynamic-online-services'),
        type: "toggle",
        optionKey: "hero_cta_enabled",
        settings: settings,
        onChange: onChange
      }), settings?.hero_cta_enabled && /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_3__.jsxs)(react_jsx_runtime__WEBPACK_IMPORTED_MODULE_3__.Fragment, {
        children: [/*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_3__.jsx)(_OptionControl__WEBPACK_IMPORTED_MODULE_2__["default"], {
          label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('Button Text', 'dynamic-online-services'),
          help: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('Text displayed on the button.', 'dynamic-online-services'),
          type: "text",
          optionKey: "hero_cta_text",
          settings: settings,
          onChange: onChange
        }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_3__.jsx)(_OptionControl__WEBPACK_IMPORTED_MODULE_2__["default"], {
          label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('Button URL', 'dynamic-online-services'),
          help: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('Link URL for the button.', 'dynamic-online-services'),
          type: "text",
          optionKey: "hero_cta_url",
          settings: settings,
          onChange: onChange
        }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_3__.jsx)(_OptionControl__WEBPACK_IMPORTED_MODULE_2__["default"], {
          label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('Button Style', 'dynamic-online-services'),
          help: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('Visual style of the button.', 'dynamic-online-services'),
          type: "select",
          optionKey: "hero_cta_style",
          options: [{
            label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('Primary', 'dynamic-online-services'),
            value: 'primary'
          }, {
            label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('Secondary', 'dynamic-online-services'),
            value: 'secondary'
          }, {
            label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('Outline', 'dynamic-online-services'),
            value: 'outline'
          }],
          settings: settings,
          onChange: onChange
        }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_3__.jsx)(_OptionControl__WEBPACK_IMPORTED_MODULE_2__["default"], {
          label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('Open in New Tab', 'dynamic-online-services'),
          help: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('Open link in a new browser tab.', 'dynamic-online-services'),
          type: "toggle",
          optionKey: "hero_cta_new_tab",
          settings: settings,
          onChange: onChange
        })]
      })]
    }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_3__.jsxs)(_wordpress_components__WEBPACK_IMPORTED_MODULE_0__.PanelBody, {
      title: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('Background Video', 'dynamic-online-services'),
      initialOpen: false,
      children: [/*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_3__.jsx)(_OptionControl__WEBPACK_IMPORTED_MODULE_2__["default"], {
        label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('Enable Video Background', 'dynamic-online-services'),
        help: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('Use a video as the hero background.', 'dynamic-online-services'),
        type: "toggle",
        optionKey: "hero_video_enabled",
        settings: settings,
        onChange: onChange
      }), settings?.hero_video_enabled && /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_3__.jsxs)(react_jsx_runtime__WEBPACK_IMPORTED_MODULE_3__.Fragment, {
        children: [/*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_3__.jsx)(_OptionControl__WEBPACK_IMPORTED_MODULE_2__["default"], {
          label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('Video URL', 'dynamic-online-services'),
          help: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('Full URL to the video file (MP4 recommended).', 'dynamic-online-services'),
          type: "text",
          optionKey: "hero_video_url",
          settings: settings,
          onChange: onChange
        }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_3__.jsx)(_OptionControl__WEBPACK_IMPORTED_MODULE_2__["default"], {
          label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('Poster Image URL', 'dynamic-online-services'),
          help: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('Fallback image shown before video loads.', 'dynamic-online-services'),
          type: "text",
          optionKey: "hero_video_poster",
          settings: settings,
          onChange: onChange
        }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_3__.jsx)(_OptionControl__WEBPACK_IMPORTED_MODULE_2__["default"], {
          label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('Autoplay', 'dynamic-online-services'),
          help: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('Automatically play video on page load.', 'dynamic-online-services'),
          type: "toggle",
          optionKey: "hero_video_autoplay",
          settings: settings,
          onChange: onChange
        }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_3__.jsx)(_OptionControl__WEBPACK_IMPORTED_MODULE_2__["default"], {
          label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('Loop', 'dynamic-online-services'),
          help: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('Loop the video continuously.', 'dynamic-online-services'),
          type: "toggle",
          optionKey: "hero_video_loop",
          settings: settings,
          onChange: onChange
        }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_3__.jsx)(_OptionControl__WEBPACK_IMPORTED_MODULE_2__["default"], {
          label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('Mute', 'dynamic-online-services'),
          help: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('Mute the video audio.', 'dynamic-online-services'),
          type: "toggle",
          optionKey: "hero_video_muted",
          settings: settings,
          onChange: onChange
        })]
      })]
    }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_3__.jsxs)(_wordpress_components__WEBPACK_IMPORTED_MODULE_0__.PanelBody, {
      title: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('Parallax Effect', 'dynamic-online-services'),
      initialOpen: false,
      children: [/*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_3__.jsx)(_OptionControl__WEBPACK_IMPORTED_MODULE_2__["default"], {
        label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('Enable Parallax', 'dynamic-online-services'),
        help: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('Apply CSS-only parallax scrolling effect to background.', 'dynamic-online-services'),
        type: "toggle",
        optionKey: "hero_parallax_enabled",
        settings: settings,
        onChange: onChange
      }), settings?.hero_parallax_enabled && /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_3__.jsx)("div", {
        className: "dynos-info-box",
        children: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('Parallax effect respects prefers-reduced-motion. Uses CSS background-attachment: fixed for GPU acceleration.', 'dynamic-online-services')
      })]
    })]
  });
};
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (HeroSettings);

/***/ }),

/***/ "./src/admin/components/LoadingSkeleton.js":
/*!*************************************************!*\
  !*** ./src/admin/components/LoadingSkeleton.js ***!
  \*************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/components */ "@wordpress/components");
/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_components__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var react_jsx_runtime__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! react/jsx-runtime */ "react/jsx-runtime");
/* harmony import */ var react_jsx_runtime__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(react_jsx_runtime__WEBPACK_IMPORTED_MODULE_1__);


/**
 * Loading Skeleton Component
 * Displays a premium skeleton loader while content is loading
 */

const LoadingSkeleton = () => {
  return /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_1__.jsx)("div", {
    className: "dynos-settings-wrap",
    children: /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_1__.jsxs)("div", {
      className: "dynos-app-container",
      children: [/*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_1__.jsxs)("aside", {
        className: "dynos-sidebar",
        children: [/*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_1__.jsx)("div", {
          className: "dynos-sidebar-header",
          children: /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_1__.jsxs)("h1", {
            children: [/*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_1__.jsx)(_wordpress_components__WEBPACK_IMPORTED_MODULE_0__.Dashicon, {
              icon: "superhero-alt"
            }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_1__.jsx)("span", {
              children: "DynOS"
            })]
          })
        }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_1__.jsx)("nav", {
          className: "dynos-sidebar-nav",
          children: [1, 2, 3, 4, 5].map(i => /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_1__.jsx)("div", {
            className: "dynos-skeleton dynos-skeleton-sidebar"
          }, i))
        }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_1__.jsx)("div", {
          className: "dynos-sidebar-footer",
          children: /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_1__.jsx)("div", {
            className: "dynos-skeleton dynos-skeleton-sidebar"
          })
        })]
      }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_1__.jsxs)("main", {
        className: "dynos-main-content",
        children: [/*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_1__.jsxs)("div", {
          className: "dynos-content-header",
          children: [/*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_1__.jsx)("div", {
            className: "dynos-skeleton",
            style: {
              width: '200px',
              height: '36px',
              marginBottom: '12px'
            }
          }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_1__.jsx)("div", {
            className: "dynos-skeleton",
            style: {
              width: '400px',
              height: '24px'
            }
          })]
        }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_1__.jsx)("div", {
          className: "dynos-panel-wrapper",
          children: [1, 2].map(i => /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_1__.jsx)("div", {
            className: "dynos-skeleton dynos-skeleton-panel"
          }, i))
        })]
      })]
    })
  });
};
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (LoadingSkeleton);

/***/ }),

/***/ "./src/admin/components/NavItem.js":
/*!*****************************************!*\
  !*** ./src/admin/components/NavItem.js ***!
  \*****************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/components */ "@wordpress/components");
/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_components__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var prop_types__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! prop-types */ "./node_modules/prop-types/index.js");
/* harmony import */ var prop_types__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(prop_types__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var react_jsx_runtime__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! react/jsx-runtime */ "react/jsx-runtime");
/* harmony import */ var react_jsx_runtime__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(react_jsx_runtime__WEBPACK_IMPORTED_MODULE_2__);



/**
 * NavItem component encapsulates a navigation button with proper ARIA attributes.
 * @param {Object}   props
 * @param {string}   props.id           - Tab identifier.
 * @param {string}   props.label        - Display label.
 * @param {string}   props.icon         - Dashicon name.
 * @param {string}   props.activeTab    - Currently active tab.
 * @param {Function} props.setActiveTab - Function to change active tab.
 */

const NavItem = ({
  id,
  label,
  icon,
  activeTab,
  setActiveTab
}) => /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_2__.jsx)(_wordpress_components__WEBPACK_IMPORTED_MODULE_0__.Tooltip, {
  content: label,
  position: "right",
  children: /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_2__.jsxs)(_wordpress_components__WEBPACK_IMPORTED_MODULE_0__.Button, {
    type: "button",
    className: `dynos-nav-item ${activeTab === id ? 'active' : ''}`,
    onClick: () => setActiveTab(id),
    "aria-label": label,
    "aria-current": activeTab === id ? 'page' : undefined,
    children: [/*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_2__.jsx)(_wordpress_components__WEBPACK_IMPORTED_MODULE_0__.Dashicon, {
      icon: icon
    }), label]
  })
});
NavItem.propTypes = {
  id: (prop_types__WEBPACK_IMPORTED_MODULE_1___default().string).isRequired,
  label: (prop_types__WEBPACK_IMPORTED_MODULE_1___default().string).isRequired,
  icon: (prop_types__WEBPACK_IMPORTED_MODULE_1___default().string).isRequired,
  activeTab: (prop_types__WEBPACK_IMPORTED_MODULE_1___default().string).isRequired,
  setActiveTab: (prop_types__WEBPACK_IMPORTED_MODULE_1___default().func).isRequired
};
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (NavItem);

/***/ }),

/***/ "./src/admin/components/OptionControl.js":
/*!***********************************************!*\
  !*** ./src/admin/components/OptionControl.js ***!
  \***********************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/components */ "@wordpress/components");
/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_components__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @wordpress/element */ "@wordpress/element");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @wordpress/i18n */ "@wordpress/i18n");
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__);
/* harmony import */ var prop_types__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! prop-types */ "./node_modules/prop-types/index.js");
/* harmony import */ var prop_types__WEBPACK_IMPORTED_MODULE_3___default = /*#__PURE__*/__webpack_require__.n(prop_types__WEBPACK_IMPORTED_MODULE_3__);
/* harmony import */ var react_jsx_runtime__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! react/jsx-runtime */ "react/jsx-runtime");
/* harmony import */ var react_jsx_runtime__WEBPACK_IMPORTED_MODULE_4___default = /*#__PURE__*/__webpack_require__.n(react_jsx_runtime__WEBPACK_IMPORTED_MODULE_4__);





/**
 * OptionControl Component.
 *
 * Renders a control for a specific setting key within the dynos_options object.
 *
 * @param {Object}   props
 * @param {string}   props.label     Label for the setting.
 * @param {string}   props.help      Help text/Description.
 * @param {string}   props.type      'text', 'number', 'color', 'toggle', 'range', 'unit', 'select', 'textarea'.
 * @param {string}   props.optionKey The key in dynos_options.
 * @param {Object}   props.settings  The full settings object.
 * @param {Function} props.onChange  Callback when value changes.
 * @param {Array}    [props.options] Optional options for 'select' type.
 */

const OptionControl = ({
  label,
  help,
  type,
  optionKey,
  settings,
  onChange,
  options
}) => {
  const value = settings[optionKey];
  const [showPopover, setShowPopover] = (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_1__.useState)(false);
  const handleChange = newValue => {
    onChange({
      ...settings,
      [optionKey]: newValue
    });
  };
  const renderControl = () => {
    switch (type) {
      case 'toggle':
      case 'checkbox':
        return /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_4__.jsx)(_wordpress_components__WEBPACK_IMPORTED_MODULE_0__.ToggleControl, {
          checked: !!value,
          onChange: handleChange
        });
      case 'color':
        return /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_4__.jsxs)("div", {
          className: "dynos-color-picker-trigger",
          children: [/*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_4__.jsx)(_wordpress_components__WEBPACK_IMPORTED_MODULE_0__.Button, {
            className: "dynos-color-swatch",
            style: {
              backgroundColor: value
            },
            onClick: () => setShowPopover(!showPopover),
            "aria-label": (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__.__)('Select color', 'dynamic-online-services')
          }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_4__.jsx)("code", {
            className: "dynos-color-code",
            children: value
          }), showPopover && /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_4__.jsx)(_wordpress_components__WEBPACK_IMPORTED_MODULE_0__.Popover, {
            position: "bottom left",
            onClose: () => setShowPopover(false),
            children: /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_4__.jsx)("div", {
              className: "dynos-popover-content",
              children: /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_4__.jsx)(_wordpress_components__WEBPACK_IMPORTED_MODULE_0__.ColorPalette, {
                colors: window.dynosSettings?.colorPresets || [{
                  name: 'Black',
                  color: '#000000'
                }, {
                  name: 'White',
                  color: '#ffffff'
                }, {
                  name: 'Red',
                  color: '#ef4444'
                }, {
                  name: 'Blue',
                  color: '#3b82f6'
                }, {
                  name: 'Green',
                  color: '#10b981'
                }],
                value: value,
                onChange: handleChange,
                clearable: true
              })
            })
          })]
        });
      case 'range':
        return /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_4__.jsx)(_wordpress_components__WEBPACK_IMPORTED_MODULE_0__.RangeControl, {
          value: value,
          onChange: handleChange,
          min: 0,
          max: 1,
          step: 0.1,
          withInputField: false
        });
      case 'unit':
        return /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_4__.jsx)(_wordpress_components__WEBPACK_IMPORTED_MODULE_0__.__experimentalUnitControl, {
          value: value,
          onChange: handleChange,
          units: [{
            value: 'px',
            label: 'px',
            default: 0
          }, {
            value: '%',
            label: '%',
            default: 0
          }, {
            value: 'vh',
            label: 'vh',
            default: 0
          }, {
            value: 'vw',
            label: 'vw',
            default: 0
          }, {
            value: 'rem',
            label: 'rem',
            default: 0
          }, {
            value: 'em',
            label: 'em',
            default: 0
          }]
        });
      case 'select':
        return /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_4__.jsx)(_wordpress_components__WEBPACK_IMPORTED_MODULE_0__.SelectControl, {
          value: value,
          options: options,
          onChange: handleChange
        });
      case 'textarea':
        return /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_4__.jsx)(_wordpress_components__WEBPACK_IMPORTED_MODULE_0__.TextareaControl, {
          value: value,
          onChange: handleChange
        });
      case 'number':
        return /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_4__.jsx)(_wordpress_components__WEBPACK_IMPORTED_MODULE_0__.TextControl, {
          type: "number",
          value: value,
          onChange: handleChange
        });
      case 'tel':
        return /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_4__.jsx)(_wordpress_components__WEBPACK_IMPORTED_MODULE_0__.TextControl, {
          type: "tel",
          value: value,
          onChange: handleChange
        });
      default:
        return /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_4__.jsx)(_wordpress_components__WEBPACK_IMPORTED_MODULE_0__.TextControl, {
          value: value || '',
          onChange: handleChange
        });
    }
  };
  return /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_4__.jsxs)("div", {
    className: "dynos-option-control",
    children: [/*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_4__.jsxs)("div", {
      className: "dynos-control-header",
      children: [/*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_4__.jsx)("label", {
        className: "dynos-control-label",
        children: label
      }), help && /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_4__.jsx)("span", {
        className: "dynos-control-help",
        children: help
      })]
    }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_4__.jsx)("div", {
      className: "dynos-control-input",
      children: renderControl()
    })]
  });
};
OptionControl.propTypes = {
  label: (prop_types__WEBPACK_IMPORTED_MODULE_3___default().string).isRequired,
  help: (prop_types__WEBPACK_IMPORTED_MODULE_3___default().string),
  type: prop_types__WEBPACK_IMPORTED_MODULE_3___default().oneOf(['text', 'number', 'color', 'toggle', 'checkbox', 'range', 'unit', 'tel', 'select', 'textarea']),
  optionKey: (prop_types__WEBPACK_IMPORTED_MODULE_3___default().string).isRequired,
  settings: (prop_types__WEBPACK_IMPORTED_MODULE_3___default().object).isRequired,
  onChange: (prop_types__WEBPACK_IMPORTED_MODULE_3___default().func).isRequired,
  options: prop_types__WEBPACK_IMPORTED_MODULE_3___default().arrayOf(prop_types__WEBPACK_IMPORTED_MODULE_3___default().shape({
    label: (prop_types__WEBPACK_IMPORTED_MODULE_3___default().string),
    value: (prop_types__WEBPACK_IMPORTED_MODULE_3___default().string)
  }))
};
OptionControl.defaultProps = {
  help: '',
  type: 'text'
};
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (OptionControl);

/***/ }),

/***/ "./src/admin/components/SuccessAnimation.js":
/*!**************************************************!*\
  !*** ./src/admin/components/SuccessAnimation.js ***!
  \**************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/element */ "@wordpress/element");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var prop_types__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! prop-types */ "./node_modules/prop-types/index.js");
/* harmony import */ var prop_types__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(prop_types__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var react_jsx_runtime__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! react/jsx-runtime */ "react/jsx-runtime");
/* harmony import */ var react_jsx_runtime__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(react_jsx_runtime__WEBPACK_IMPORTED_MODULE_2__);



/**
 * Success Animation Component
 * Displays an animated checkmark for successful operations
 * @param root0
 * @param root0.show
 * @param root0.onComplete
 */

const SuccessAnimation = ({
  show = false,
  onComplete
}) => {
  const [visible, setVisible] = (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.useState)(show);
  (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.useEffect)(() => {
    if (show) {
      setVisible(true);
      const timer = setTimeout(() => {
        setVisible(false);
        if (onComplete) {
          onComplete();
        }
      }, 2000);
      return () => clearTimeout(timer);
    }
    return undefined;
  }, [show, onComplete]);
  if (!visible) {
    return null;
  }
  return /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_2__.jsx)("span", {
    className: "dynos-success-checkmark",
    role: "img",
    "aria-label": "Success",
    children: /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_2__.jsxs)("svg", {
      viewBox: "0 0 52 52",
      xmlns: "http://www.w3.org/2000/svg",
      children: [/*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_2__.jsx)("circle", {
        cx: "26",
        cy: "26",
        r: "25",
        fill: "none",
        stroke: "currentColor",
        strokeWidth: "2"
      }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_2__.jsx)("path", {
        fill: "none",
        stroke: "currentColor",
        strokeWidth: "3",
        strokeLinecap: "round",
        strokeLinejoin: "round",
        d: "M14 27l7.5 7.5L38 18"
      })]
    })
  });
};
SuccessAnimation.propTypes = {
  show: (prop_types__WEBPACK_IMPORTED_MODULE_1___default().bool),
  onComplete: (prop_types__WEBPACK_IMPORTED_MODULE_1___default().func)
};
SuccessAnimation.defaultProps = {
  show: false,
  onComplete: null
};
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (SuccessAnimation);

/***/ }),

/***/ "./src/admin/components/WhatsAppSettings.js":
/*!**************************************************!*\
  !*** ./src/admin/components/WhatsAppSettings.js ***!
  \**************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/components */ "@wordpress/components");
/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_components__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @wordpress/i18n */ "@wordpress/i18n");
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var _OptionControl__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./OptionControl */ "./src/admin/components/OptionControl.js");
/* harmony import */ var react_jsx_runtime__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! react/jsx-runtime */ "react/jsx-runtime");
/* harmony import */ var react_jsx_runtime__WEBPACK_IMPORTED_MODULE_3___default = /*#__PURE__*/__webpack_require__.n(react_jsx_runtime__WEBPACK_IMPORTED_MODULE_3__);




const WhatsAppSettings = ({
  settings,
  onChange
}) => {
  const handleAgentChange = (index, agentUpdates) => {
    const newAgents = [...(settings.whatsapp_agents || [])];
    newAgents[index] = {
      ...newAgents[index],
      ...agentUpdates
    };
    onChange({
      ...settings,
      whatsapp_agents: newAgents
    });
  };
  const removeAgent = index => {
    const newAgents = [...(settings.whatsapp_agents || [])];
    newAgents.splice(index, 1);
    onChange({
      ...settings,
      whatsapp_agents: newAgents
    });
  };
  const addAgent = () => {
    const newAgents = [...(settings.whatsapp_agents || []), {
      name: '',
      number: '',
      label: '',
      avatar_url: ''
    }];
    onChange({
      ...settings,
      whatsapp_agents: newAgents
    });
  };
  return /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_3__.jsxs)("div", {
    className: "dynos-tab-content",
    children: [/*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_3__.jsx)("h2", {
      children: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('WhatsApp Configuration', 'dynamic-online-services')
    }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_3__.jsxs)(_wordpress_components__WEBPACK_IMPORTED_MODULE_0__.PanelBody, {
      title: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('General Settings', 'dynamic-online-services'),
      initialOpen: true,
      children: [/*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_3__.jsx)(_OptionControl__WEBPACK_IMPORTED_MODULE_2__["default"], {
        label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('Enable Floating Button', 'dynamic-online-services'),
        help: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('Display the WhatsApp button on the frontend.', 'dynamic-online-services'),
        type: "toggle",
        optionKey: "whatsapp_enabled",
        settings: settings,
        onChange: onChange
      }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_3__.jsx)(_OptionControl__WEBPACK_IMPORTED_MODULE_2__["default"], {
        label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('Phone Number', 'dynamic-online-services'),
        help: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('Enter number with country code (e.g., 923001234567).', 'dynamic-online-services'),
        type: "tel",
        optionKey: "whatsapp_number",
        settings: settings,
        onChange: onChange
      }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_3__.jsx)(_OptionControl__WEBPACK_IMPORTED_MODULE_2__["default"], {
        label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('Pre-filled Message', 'dynamic-online-services'),
        help: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('Message to send when user clicks the button. Supports {current_page_url}, {page_title}.', 'dynamic-online-services'),
        type: "textarea",
        optionKey: "whatsapp_message",
        settings: settings,
        onChange: onChange
      })]
    }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_3__.jsxs)(_wordpress_components__WEBPACK_IMPORTED_MODULE_0__.PanelBody, {
      title: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('Appearance & Position', 'dynamic-online-services'),
      initialOpen: false,
      children: [/*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_3__.jsxs)("div", {
        className: "dynos-grid-2",
        children: [/*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_3__.jsx)(_OptionControl__WEBPACK_IMPORTED_MODULE_2__["default"], {
          label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('Icon Style', 'dynamic-online-services'),
          type: "select",
          optionKey: "whatsapp_icon_style",
          options: [{
            label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('Official Logo', 'dynamic-online-services'),
            value: 'default'
          }, {
            label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('Chat Bubble', 'dynamic-online-services'),
            value: 'chat'
          }, {
            label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('User Avatar (Placeholder)', 'dynamic-online-services'),
            value: 'avatar'
          }],
          settings: settings,
          onChange: onChange
        }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_3__.jsx)(_OptionControl__WEBPACK_IMPORTED_MODULE_2__["default"], {
          label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('Position', 'dynamic-online-services'),
          type: "select",
          optionKey: "whatsapp_position",
          options: [{
            label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('Bottom Right', 'dynamic-online-services'),
            value: 'right'
          }, {
            label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('Bottom Left', 'dynamic-online-services'),
            value: 'left'
          }],
          settings: settings,
          onChange: onChange
        })]
      }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_3__.jsxs)("div", {
        className: "dynos-grid-2",
        children: [/*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_3__.jsx)(_OptionControl__WEBPACK_IMPORTED_MODULE_2__["default"], {
          label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('Horizontal Offset', 'dynamic-online-services'),
          help: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('From side edge (e.g., 20px).', 'dynamic-online-services'),
          type: "text",
          optionKey: "whatsapp_position_offset_x",
          settings: settings,
          onChange: onChange
        }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_3__.jsx)(_OptionControl__WEBPACK_IMPORTED_MODULE_2__["default"], {
          label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('Vertical Offset', 'dynamic-online-services'),
          help: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('From bottom edge (e.g., 20px).', 'dynamic-online-services'),
          type: "text",
          optionKey: "whatsapp_position_offset_y",
          settings: settings,
          onChange: onChange
        })]
      }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_3__.jsx)(_OptionControl__WEBPACK_IMPORTED_MODULE_2__["default"], {
        label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('Background Color', 'dynamic-online-services'),
        type: "color",
        optionKey: "whatsapp_bg_color",
        settings: settings,
        onChange: onChange
      }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_3__.jsx)(_OptionControl__WEBPACK_IMPORTED_MODULE_2__["default"], {
        label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('Icon Color', 'dynamic-online-services'),
        type: "color",
        optionKey: "whatsapp_icon_color",
        settings: settings,
        onChange: onChange
      })]
    }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_3__.jsxs)(_wordpress_components__WEBPACK_IMPORTED_MODULE_0__.PanelBody, {
      title: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('Call to Action (CTA)', 'dynamic-online-services'),
      initialOpen: false,
      children: [/*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_3__.jsx)(_OptionControl__WEBPACK_IMPORTED_MODULE_2__["default"], {
        label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('Enable CTA Bubble', 'dynamic-online-services'),
        help: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('Show a bubble next to the button.', 'dynamic-online-services'),
        type: "toggle",
        optionKey: "whatsapp_cta_enabled",
        settings: settings,
        onChange: onChange
      }), settings.whatsapp_cta_enabled && /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_3__.jsxs)(react_jsx_runtime__WEBPACK_IMPORTED_MODULE_3__.Fragment, {
        children: [/*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_3__.jsx)(_OptionControl__WEBPACK_IMPORTED_MODULE_2__["default"], {
          label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('CTA Text', 'dynamic-online-services'),
          type: "text",
          optionKey: "whatsapp_cta_text",
          settings: settings,
          onChange: onChange
        }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_3__.jsx)(_OptionControl__WEBPACK_IMPORTED_MODULE_2__["default"], {
          label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('Delay (Seconds)', 'dynamic-online-services'),
          help: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('Delay before showing. 0 for immediate.', 'dynamic-online-services'),
          type: "number",
          optionKey: "whatsapp_cta_delay",
          settings: settings,
          onChange: onChange
        })]
      })]
    }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_3__.jsxs)(_wordpress_components__WEBPACK_IMPORTED_MODULE_0__.PanelBody, {
      title: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('Visibility & Schedule', 'dynamic-online-services'),
      initialOpen: false,
      children: [/*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_3__.jsxs)("div", {
        className: "dynos-grid-2",
        children: [/*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_3__.jsx)(_OptionControl__WEBPACK_IMPORTED_MODULE_2__["default"], {
          label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('Show On Pages', 'dynamic-online-services'),
          type: "select",
          optionKey: "whatsapp_visibility",
          options: [{
            label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('All Pages', 'dynamic-online-services'),
            value: 'all'
          }, {
            label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('Home Page Only', 'dynamic-online-services'),
            value: 'home'
          }],
          settings: settings,
          onChange: onChange
        }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_3__.jsx)(_OptionControl__WEBPACK_IMPORTED_MODULE_2__["default"], {
          label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('Enable Analytics', 'dynamic-online-services'),
          help: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('Fire GA/FB events.', 'dynamic-online-services'),
          type: "toggle",
          optionKey: "whatsapp_analytics_enabled",
          settings: settings,
          onChange: onChange
        })]
      }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_3__.jsxs)("div", {
        className: "dynos-grid-2",
        children: [/*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_3__.jsx)(_OptionControl__WEBPACK_IMPORTED_MODULE_2__["default"], {
          label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('Show on Desktop', 'dynamic-online-services'),
          type: "toggle",
          optionKey: "whatsapp_show_desktop",
          settings: settings,
          onChange: onChange
        }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_3__.jsx)(_OptionControl__WEBPACK_IMPORTED_MODULE_2__["default"], {
          label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('Show on Mobile', 'dynamic-online-services'),
          type: "toggle",
          optionKey: "whatsapp_show_mobile",
          settings: settings,
          onChange: onChange
        })]
      }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_3__.jsx)("div", {
        className: "dynos-divider"
      }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_3__.jsx)(_OptionControl__WEBPACK_IMPORTED_MODULE_2__["default"], {
        label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('Enable Schedule', 'dynamic-online-services'),
        help: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('Limit button availability to specific hours.', 'dynamic-online-services'),
        type: "toggle",
        optionKey: "whatsapp_availability",
        settings: settings,
        onChange: onChange
      }), settings.whatsapp_availability && /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_3__.jsxs)(react_jsx_runtime__WEBPACK_IMPORTED_MODULE_3__.Fragment, {
        children: [/*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_3__.jsxs)("div", {
          className: "dynos-grid-2",
          children: [/*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_3__.jsx)(_OptionControl__WEBPACK_IMPORTED_MODULE_2__["default"], {
            label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('Start Time', 'dynamic-online-services'),
            type: "text",
            optionKey: "whatsapp_schedule_start",
            settings: settings,
            onChange: onChange
          }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_3__.jsx)(_OptionControl__WEBPACK_IMPORTED_MODULE_2__["default"], {
            label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('End Time', 'dynamic-online-services'),
            type: "text",
            optionKey: "whatsapp_schedule_end",
            settings: settings,
            onChange: onChange
          })]
        }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_3__.jsx)(_OptionControl__WEBPACK_IMPORTED_MODULE_2__["default"], {
          label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('Timezone', 'dynamic-online-services'),
          type: "select",
          optionKey: "whatsapp_timezone",
          options: window.dynosSettings?.timezones || [{
            label: 'UTC',
            value: 'UTC'
          }],
          help: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('Select your timezone.', 'dynamic-online-services'),
          settings: settings,
          onChange: onChange
        }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_3__.jsx)("div", {
          className: "dynos-divider"
        }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_3__.jsx)(_OptionControl__WEBPACK_IMPORTED_MODULE_2__["default"], {
          label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('Offline Behavior', 'dynamic-online-services'),
          type: "select",
          optionKey: "whatsapp_offline_behavior",
          options: [{
            label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('Hide Button', 'dynamic-online-services'),
            value: 'hide'
          }, {
            label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('Show Offline Message', 'dynamic-online-services'),
            value: 'show'
          }],
          help: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('What to do when outside business hours.', 'dynamic-online-services'),
          settings: settings,
          onChange: onChange
        }), settings.whatsapp_offline_behavior === 'show' && /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_3__.jsx)(_OptionControl__WEBPACK_IMPORTED_MODULE_2__["default"], {
          label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('Offline Message', 'dynamic-online-services'),
          type: "textarea",
          optionKey: "whatsapp_offline_text",
          help: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('Message to display when offline.', 'dynamic-online-services'),
          settings: settings,
          onChange: onChange
        })]
      })]
    }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_3__.jsxs)(_wordpress_components__WEBPACK_IMPORTED_MODULE_0__.PanelBody, {
      title: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('Multi-Agent Support', 'dynamic-online-services'),
      initialOpen: false,
      children: [/*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_3__.jsx)(_OptionControl__WEBPACK_IMPORTED_MODULE_2__["default"], {
        label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('Enable Multiple Agents', 'dynamic-online-services'),
        help: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('Show a list of agents instead of direct chat.', 'dynamic-online-services'),
        type: "toggle",
        optionKey: "whatsapp_agents_enabled",
        settings: settings,
        onChange: onChange
      }), settings.whatsapp_agents_enabled && /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_3__.jsxs)("div", {
        className: "dynos-agents-list",
        children: [(settings.whatsapp_agents || []).map((agent, index) => /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_3__.jsxs)("div", {
          className: "dynos-agent-item",
          children: [/*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_3__.jsxs)("div", {
            className: "dynos-agent-header",
            children: [/*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_3__.jsx)("h4", {
              children: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.sprintf)(/* translators: %d is the agent number */
              (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('Agent %d', 'dynamic-online-services'), index + 1)
            }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_3__.jsx)(_wordpress_components__WEBPACK_IMPORTED_MODULE_0__.Button, {
              isDestructive: true,
              isSmall: true,
              variant: "secondary",
              onClick: () => removeAgent(index),
              children: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('Remove', 'dynamic-online-services')
            })]
          }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_3__.jsxs)("div", {
            className: "dynos-grid-2",
            children: [/*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_3__.jsx)(_OptionControl__WEBPACK_IMPORTED_MODULE_2__["default"], {
              label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('Name', 'dynamic-online-services'),
              type: "text",
              optionKey: "name",
              settings: agent,
              onChange: val => handleAgentChange(index, {
                name: val.name
              })
            }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_3__.jsx)(_OptionControl__WEBPACK_IMPORTED_MODULE_2__["default"], {
              label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('Number (e.g., 92300…)', 'dynamic-online-services'),
              type: "tel",
              optionKey: "number",
              settings: agent,
              onChange: val => handleAgentChange(index, {
                number: val.number
              })
            })]
          }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_3__.jsx)(_OptionControl__WEBPACK_IMPORTED_MODULE_2__["default"], {
            label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('Role / Label', 'dynamic-online-services'),
            type: "text",
            optionKey: "label",
            help: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('e.g. Sales Support', 'dynamic-online-services'),
            settings: agent,
            onChange: val => handleAgentChange(index, {
              label: val.label
            })
          }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_3__.jsx)(_OptionControl__WEBPACK_IMPORTED_MODULE_2__["default"], {
            label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('Avatar URL (Optional)', 'dynamic-online-services'),
            type: "text",
            optionKey: "avatar_url",
            help: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('https://example.com/avatar.jpg', 'dynamic-online-services'),
            settings: agent,
            onChange: val => handleAgentChange(index, {
              avatar_url: val.avatar_url
            })
          })]
        }, index)), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_3__.jsx)(_wordpress_components__WEBPACK_IMPORTED_MODULE_0__.Button, {
          isSecondary: true,
          onClick: addAgent,
          children: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('Add New Agent', 'dynamic-online-services')
        })]
      })]
    })]
  });
};
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (WhatsAppSettings);

/***/ }),

/***/ "./src/admin/constants.js":
/*!********************************!*\
  !*** ./src/admin/constants.js ***!
  \********************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   ANIMATION_DURATION: () => (/* binding */ ANIMATION_DURATION),
/* harmony export */   BREAKPOINTS: () => (/* binding */ BREAKPOINTS),
/* harmony export */   COLOR_PRESETS: () => (/* binding */ COLOR_PRESETS),
/* harmony export */   KEYBOARD_SHORTCUTS: () => (/* binding */ KEYBOARD_SHORTCUTS),
/* harmony export */   SEMANTIC_COLORS: () => (/* binding */ SEMANTIC_COLORS),
/* harmony export */   TABS: () => (/* binding */ TABS)
/* harmony export */ });
/**
 * Dynamic Online Services - Admin Constants
 * Centralized constants for better maintainability and type safety
 */

/**
 * Tab IDs for admin settings interface
 */
const TABS = {
  GENERAL: 'general',
  HERO: 'hero',
  CARDS: 'cards',
  FAQS: 'faqs',
  WHATSAPP: 'whatsapp'
};

/**
 * Keyboard shortcuts
 */
const KEYBOARD_SHORTCUTS = {
  SAVE: 's' // Will be used with Ctrl/Cmd modifier
};

/**
 * Color presets for color picker
 */
const COLOR_PRESETS = [{
  name: 'Black',
  color: '#000000'
}, {
  name: 'White',
  color: '#ffffff'
}, {
  name: 'Slate 900',
  color: '#0f172a'
}, {
  name: 'Slate 600',
  color: '#475569'
}, {
  name: 'Slate 400',
  color: '#94a3b8'
}, {
  name: 'Indigo 600',
  color: '#4f46e5'
}, {
  name: 'Indigo 500',
  color: '#6366f1'
}, {
  name: 'Rose 600',
  color: '#e11d48'
}, {
  name: 'Rose 500',
  color: '#f43f5e'
}, {
  name: 'Green 500',
  color: '#10b981'
}, {
  name: 'Blue 500',
  color: '#3b82f6'
}, {
  name: 'Red 500',
  color: '#ef4444'
}, {
  name: 'Amber 500',
  color: '#f59e0b'
}];

/**
 * Semantic color tokens (matching CSS custom properties)
 */
const SEMANTIC_COLORS = {
  PRIMARY: '#4f46e5',
  PRIMARY_HOVER: '#4338ca',
  ACCENT: '#e11d48',
  SUCCESS: '#10b981',
  WARNING: '#f59e0b',
  DANGER: '#ef4444'
};

/**
 * Animation durations (in milliseconds)
 */
const ANIMATION_DURATION = {
  FAST: 150,
  NORMAL: 200,
  SLOW: 350,
  SUCCESS_ANIMATION: 2000
};

/**
 * Breakpoints (should match CSS)
 */
const BREAKPOINTS = {
  MOBILE: 600,
  TABLET: 960,
  DESKTOP: 1400
};

/***/ }),

/***/ "./src/admin/hooks/useKeyboardShortcut.js":
/*!************************************************!*\
  !*** ./src/admin/hooks/useKeyboardShortcut.js ***!
  \************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   useKeyboardShortcut: () => (/* binding */ useKeyboardShortcut)
/* harmony export */ });
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/element */ "@wordpress/element");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__);


/**
 * Custom hook for keyboard shortcuts
 *
 * @param {string}   key                    - The key to listen for (without modifier)
 * @param {Function} callback               - Function to call when shortcut is pressed
 * @param {Object}   options                - Configuration options
 * @param {boolean}  options.requireCtrl    - Require Ctrl/Cmd key (default: true)
 * @param {boolean}  options.preventDefault - Prevent default behavior (default: true)
 */
const useKeyboardShortcut = (key, callback, {
  requireCtrl = true,
  preventDefault = true
} = {}) => {
  (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.useEffect)(() => {
    const handler = event => {
      // Check if the key matches
      const keyMatches = event.key.toLowerCase() === key.toLowerCase();

      // Check modifier requirement
      const modifierMatches = requireCtrl ? event.metaKey || event.ctrlKey : true;

      // Don't trigger if user is typing in an input
      const isTyping = ['INPUT', 'TEXTAREA', 'SELECT'].includes(event.target.tagName);
      if (keyMatches && modifierMatches && !isTyping) {
        if (preventDefault) {
          event.preventDefault();
        }
        callback(event);
      }
    };
    document.addEventListener('keydown', handler);
    return () => {
      document.removeEventListener('keydown', handler);
    };
  }, [key, callback, requireCtrl, preventDefault]);
};

/***/ }),

/***/ "./src/index.css":
/*!***********************!*\
  !*** ./src/index.css ***!
  \***********************/
/***/ (() => {

throw new Error("Module build failed (from ./node_modules/mini-css-extract-plugin/dist/loader.js):\nHookWebpackError: Module build failed: Error: ENOENT: no such file or directory, open '/Users/syedaalin/Documents/dynamic-online-services/node_modules/css-loader/dist/runtime/sourceMaps.js'\n    at tryRunOrWebpackError (/Users/syedaalin/Documents/dynamic-online-services/node_modules/webpack/lib/HookWebpackError.js:85:9)\n    at __webpack_require_module__ (/Users/syedaalin/Documents/dynamic-online-services/node_modules/webpack/lib/Compilation.js:5564:12)\n    at __webpack_require__ (/Users/syedaalin/Documents/dynamic-online-services/node_modules/webpack/lib/Compilation.js:5511:18)\n    at Module.<anonymous> (/Users/syedaalin/Documents/dynamic-online-services/node_modules/css-loader/dist/cjs.js??ruleSet[1].rules[2].use[1]!/Users/syedaalin/Documents/dynamic-online-services/node_modules/postcss-loader/dist/cjs.js??ruleSet[1].rules[2].use[2]!/Users/syedaalin/Documents/dynamic-online-services/src/index.css:5:109)\n    at /Users/syedaalin/Documents/dynamic-online-services/node_modules/webpack/lib/javascript/JavascriptModulesPlugin.js:548:10\n    at Hook.eval [as call] (eval at create (/Users/syedaalin/Documents/dynamic-online-services/node_modules/tapable/lib/HookCodeFactory.js:19:10), <anonymous>:7:1)\n    at /Users/syedaalin/Documents/dynamic-online-services/node_modules/webpack/lib/Compilation.js:5566:39\n    at tryRunOrWebpackError (/Users/syedaalin/Documents/dynamic-online-services/node_modules/webpack/lib/HookWebpackError.js:80:7)\n    at __webpack_require_module__ (/Users/syedaalin/Documents/dynamic-online-services/node_modules/webpack/lib/Compilation.js:5564:12)\n    at __webpack_require__ (/Users/syedaalin/Documents/dynamic-online-services/node_modules/webpack/lib/Compilation.js:5511:18)\n    at /Users/syedaalin/Documents/dynamic-online-services/node_modules/webpack/lib/Compilation.js:5599:20\n    at symbolIterator (/Users/syedaalin/Documents/dynamic-online-services/node_modules/neo-async/async.js:3485:9)\n    at done (/Users/syedaalin/Documents/dynamic-online-services/node_modules/neo-async/async.js:3527:9)\n    at Hook.eval [as callAsync] (eval at create (/Users/syedaalin/Documents/dynamic-online-services/node_modules/tapable/lib/HookCodeFactory.js:31:10), <anonymous>:15:1)\n    at /Users/syedaalin/Documents/dynamic-online-services/node_modules/webpack/lib/Compilation.js:5486:43\n    at symbolIterator (/Users/syedaalin/Documents/dynamic-online-services/node_modules/neo-async/async.js:3482:9)\n    at timesSync (/Users/syedaalin/Documents/dynamic-online-services/node_modules/neo-async/async.js:2297:7)\n    at Object.eachLimit (/Users/syedaalin/Documents/dynamic-online-services/node_modules/neo-async/async.js:3463:5)\n    at /Users/syedaalin/Documents/dynamic-online-services/node_modules/webpack/lib/Compilation.js:5449:16\n    at symbolIterator (/Users/syedaalin/Documents/dynamic-online-services/node_modules/neo-async/async.js:3485:9)\n    at timesSync (/Users/syedaalin/Documents/dynamic-online-services/node_modules/neo-async/async.js:2297:7)\n    at Object.eachLimit (/Users/syedaalin/Documents/dynamic-online-services/node_modules/neo-async/async.js:3463:5)\n    at /Users/syedaalin/Documents/dynamic-online-services/node_modules/webpack/lib/Compilation.js:5417:15\n    at symbolIterator (/Users/syedaalin/Documents/dynamic-online-services/node_modules/neo-async/async.js:3485:9)\n    at done (/Users/syedaalin/Documents/dynamic-online-services/node_modules/neo-async/async.js:3527:9)\n    at /Users/syedaalin/Documents/dynamic-online-services/node_modules/webpack/lib/Compilation.js:5363:8\n    at /Users/syedaalin/Documents/dynamic-online-services/node_modules/webpack/lib/Compilation.js:3770:5\n    at /Users/syedaalin/Documents/dynamic-online-services/node_modules/webpack/lib/Cache.js:100:5\n    at Hook.eval [as callAsync] (eval at create (/Users/syedaalin/Documents/dynamic-online-services/node_modules/tapable/lib/HookCodeFactory.js:31:10), <anonymous>:16:1)\n    at Cache.get (/Users/syedaalin/Documents/dynamic-online-services/node_modules/webpack/lib/Cache.js:82:18)\n    at ItemCacheFacade.get (/Users/syedaalin/Documents/dynamic-online-services/node_modules/webpack/lib/CacheFacade.js:115:15)\n    at Compilation._codeGenerationModule (/Users/syedaalin/Documents/dynamic-online-services/node_modules/webpack/lib/Compilation.js:3734:9)\n    at codeGen (/Users/syedaalin/Documents/dynamic-online-services/node_modules/webpack/lib/Compilation.js:5351:11)\n    at symbolIterator (/Users/syedaalin/Documents/dynamic-online-services/node_modules/neo-async/async.js:3482:9)\n    at timesSync (/Users/syedaalin/Documents/dynamic-online-services/node_modules/neo-async/async.js:2297:7)\n    at Object.eachLimit (/Users/syedaalin/Documents/dynamic-online-services/node_modules/neo-async/async.js:3463:5)\n    at /Users/syedaalin/Documents/dynamic-online-services/node_modules/webpack/lib/Compilation.js:5381:14\n    at processQueue (/Users/syedaalin/Documents/dynamic-online-services/node_modules/webpack/lib/util/processAsyncTree.js:61:4)\n    at process.processTicksAndRejections (node:internal/process/task_queues:84:11)\n-- inner error --\nError: Module build failed: Error: ENOENT: no such file or directory, open '/Users/syedaalin/Documents/dynamic-online-services/node_modules/css-loader/dist/runtime/sourceMaps.js'\n    at Object.<anonymous> (/Users/syedaalin/Documents/dynamic-online-services/node_modules/css-loader/dist/runtime/sourceMaps.js:1:7)\n    at /Users/syedaalin/Documents/dynamic-online-services/node_modules/webpack/lib/javascript/JavascriptModulesPlugin.js:548:10\n    at Hook.eval [as call] (eval at create (/Users/syedaalin/Documents/dynamic-online-services/node_modules/tapable/lib/HookCodeFactory.js:19:10), <anonymous>:7:1)\n    at /Users/syedaalin/Documents/dynamic-online-services/node_modules/webpack/lib/Compilation.js:5566:39\n    at tryRunOrWebpackError (/Users/syedaalin/Documents/dynamic-online-services/node_modules/webpack/lib/HookWebpackError.js:80:7)\n    at __webpack_require_module__ (/Users/syedaalin/Documents/dynamic-online-services/node_modules/webpack/lib/Compilation.js:5564:12)\n    at __webpack_require__ (/Users/syedaalin/Documents/dynamic-online-services/node_modules/webpack/lib/Compilation.js:5511:18)\n    at Module.<anonymous> (/Users/syedaalin/Documents/dynamic-online-services/node_modules/css-loader/dist/cjs.js??ruleSet[1].rules[2].use[1]!/Users/syedaalin/Documents/dynamic-online-services/node_modules/postcss-loader/dist/cjs.js??ruleSet[1].rules[2].use[2]!/Users/syedaalin/Documents/dynamic-online-services/src/index.css:5:109)\n    at /Users/syedaalin/Documents/dynamic-online-services/node_modules/webpack/lib/javascript/JavascriptModulesPlugin.js:548:10\n    at Hook.eval [as call] (eval at create (/Users/syedaalin/Documents/dynamic-online-services/node_modules/tapable/lib/HookCodeFactory.js:19:10), <anonymous>:7:1)\n    at /Users/syedaalin/Documents/dynamic-online-services/node_modules/webpack/lib/Compilation.js:5566:39\n    at tryRunOrWebpackError (/Users/syedaalin/Documents/dynamic-online-services/node_modules/webpack/lib/HookWebpackError.js:80:7)\n    at __webpack_require_module__ (/Users/syedaalin/Documents/dynamic-online-services/node_modules/webpack/lib/Compilation.js:5564:12)\n    at __webpack_require__ (/Users/syedaalin/Documents/dynamic-online-services/node_modules/webpack/lib/Compilation.js:5511:18)\n    at /Users/syedaalin/Documents/dynamic-online-services/node_modules/webpack/lib/Compilation.js:5599:20\n    at symbolIterator (/Users/syedaalin/Documents/dynamic-online-services/node_modules/neo-async/async.js:3485:9)\n    at done (/Users/syedaalin/Documents/dynamic-online-services/node_modules/neo-async/async.js:3527:9)\n    at Hook.eval [as callAsync] (eval at create (/Users/syedaalin/Documents/dynamic-online-services/node_modules/tapable/lib/HookCodeFactory.js:31:10), <anonymous>:15:1)\n    at /Users/syedaalin/Documents/dynamic-online-services/node_modules/webpack/lib/Compilation.js:5486:43\n    at symbolIterator (/Users/syedaalin/Documents/dynamic-online-services/node_modules/neo-async/async.js:3482:9)\n    at timesSync (/Users/syedaalin/Documents/dynamic-online-services/node_modules/neo-async/async.js:2297:7)\n    at Object.eachLimit (/Users/syedaalin/Documents/dynamic-online-services/node_modules/neo-async/async.js:3463:5)\n    at /Users/syedaalin/Documents/dynamic-online-services/node_modules/webpack/lib/Compilation.js:5449:16\n    at symbolIterator (/Users/syedaalin/Documents/dynamic-online-services/node_modules/neo-async/async.js:3485:9)\n    at timesSync (/Users/syedaalin/Documents/dynamic-online-services/node_modules/neo-async/async.js:2297:7)\n    at Object.eachLimit (/Users/syedaalin/Documents/dynamic-online-services/node_modules/neo-async/async.js:3463:5)\n    at /Users/syedaalin/Documents/dynamic-online-services/node_modules/webpack/lib/Compilation.js:5417:15\n    at symbolIterator (/Users/syedaalin/Documents/dynamic-online-services/node_modules/neo-async/async.js:3485:9)\n    at done (/Users/syedaalin/Documents/dynamic-online-services/node_modules/neo-async/async.js:3527:9)\n    at /Users/syedaalin/Documents/dynamic-online-services/node_modules/webpack/lib/Compilation.js:5363:8\n    at /Users/syedaalin/Documents/dynamic-online-services/node_modules/webpack/lib/Compilation.js:3770:5\n    at /Users/syedaalin/Documents/dynamic-online-services/node_modules/webpack/lib/Cache.js:100:5\n    at Hook.eval [as callAsync] (eval at create (/Users/syedaalin/Documents/dynamic-online-services/node_modules/tapable/lib/HookCodeFactory.js:31:10), <anonymous>:16:1)\n    at Cache.get (/Users/syedaalin/Documents/dynamic-online-services/node_modules/webpack/lib/Cache.js:82:18)\n    at ItemCacheFacade.get (/Users/syedaalin/Documents/dynamic-online-services/node_modules/webpack/lib/CacheFacade.js:115:15)\n    at Compilation._codeGenerationModule (/Users/syedaalin/Documents/dynamic-online-services/node_modules/webpack/lib/Compilation.js:3734:9)\n    at codeGen (/Users/syedaalin/Documents/dynamic-online-services/node_modules/webpack/lib/Compilation.js:5351:11)\n    at symbolIterator (/Users/syedaalin/Documents/dynamic-online-services/node_modules/neo-async/async.js:3482:9)\n    at timesSync (/Users/syedaalin/Documents/dynamic-online-services/node_modules/neo-async/async.js:2297:7)\n    at Object.eachLimit (/Users/syedaalin/Documents/dynamic-online-services/node_modules/neo-async/async.js:3463:5)\n    at /Users/syedaalin/Documents/dynamic-online-services/node_modules/webpack/lib/Compilation.js:5381:14\n    at processQueue (/Users/syedaalin/Documents/dynamic-online-services/node_modules/webpack/lib/util/processAsyncTree.js:61:4)\n    at process.processTicksAndRejections (node:internal/process/task_queues:84:11)\n\nGenerated code for /Users/syedaalin/Documents/dynamic-online-services/node_modules/css-loader/dist/runtime/sourceMaps.js\n1 | throw new Error(\"Module build failed: Error: ENOENT: no such file or directory, open '/Users/syedaalin/Documents/dynamic-online-services/node_modules/css-loader/dist/runtime/sourceMaps.js'\");\n\nGenerated code for /Users/syedaalin/Documents/dynamic-online-services/node_modules/css-loader/dist/cjs.js??ruleSet[1].rules[2].use[1]!/Users/syedaalin/Documents/dynamic-online-services/node_modules/postcss-loader/dist/cjs.js??ruleSet[1].rules[2].use[2]!/Users/syedaalin/Documents/dynamic-online-services/src/index.css\n  1 | __webpack_require__.r(__webpack_exports__);\n  2 | /* harmony export */ __webpack_require__.d(__webpack_exports__, {\n  3 | /* harmony export */   \"default\": () => (__WEBPACK_DEFAULT_EXPORT__)\n  4 | /* harmony export */ });\n  5 | /* harmony import */ var _node_modules_css_loader_dist_runtime_sourceMaps_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../node_modules/css-loader/dist/runtime/sourceMaps.js */ \"/Users/syedaalin/Documents/dynamic-online-services/node_modules/css-loader/dist/runtime/sourceMaps.js\");\n  6 | /* harmony import */ var _node_modules_css_loader_dist_runtime_sourceMaps_js__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_node_modules_css_loader_dist_runtime_sourceMaps_js__WEBPACK_IMPORTED_MODULE_0__);\n  7 | /* harmony import */ var _node_modules_css_loader_dist_runtime_api_js__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ../node_modules/css-loader/dist/runtime/api.js */ \"/Users/syedaalin/Documents/dynamic-online-services/node_modules/css-loader/dist/runtime/api.js\");\n  8 | /* harmony import */ var _node_modules_css_loader_dist_runtime_api_js__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_node_modules_css_loader_dist_runtime_api_js__WEBPACK_IMPORTED_MODULE_1__);\n  9 | // Imports\n 10 | \n 11 | \n 12 | var ___CSS_LOADER_EXPORT___ = _node_modules_css_loader_dist_runtime_api_js__WEBPACK_IMPORTED_MODULE_1___default()((_node_modules_css_loader_dist_runtime_sourceMaps_js__WEBPACK_IMPORTED_MODULE_0___default()));\n 13 | // Module\n 14 | ___CSS_LOADER_EXPORT___.push([module.id, `.dynos-tab-content {\n 15 | \tbackground: var(--wp--preset--color--background, #fff);\n 16 | \tpadding: 20px;\n 17 | \tborder: 1px solid #c3c4c7;\n 18 | \tborder-top: none;\n 19 | }\n 20 | \n 21 | /* Tab Navigation Styles */\n 22 | \n 23 | .dynos-settings-tabs .components-tab-panel__tabs {\n 24 | \tdisplay: flex;\n 25 | \tgap: 4px;\n 26 | \tborder-bottom: 1px solid #c3c4c7;\n 27 | \tmargin-bottom: 0 !important;\n 28 | \n 29 | \t/* Override default TabPanel margin */\n 30 | \tpadding-inline-start: 10px;\n 31 | }\n 32 | \n 33 | .dynos-settings-tabs .components-tab-panel__tabs .components-button {\n 34 | \tbackground: #e5e5e5;\n 35 | \tborder: 1px solid transparent;\n 36 | \tborder-bottom: none;\n 37 | \tborder-radius: 4px 4px 0 0;\n 38 | \tmargin-bottom: -1px;\n 39 | \n 40 | \t/* Overlap the container border */\n 41 | \tpadding: 10px 16px;\n 42 | \tfont-weight: 500;\n 43 | \tcolor: #50575e;\n 44 | \ttransition: all 0.2s ease;\n 45 | }\n 46 | \n 47 | .dynos-settings-tabs .components-tab-panel__tabs .components-button:hover {\n 48 | \tbackground: #fff;\n 49 | \tcolor: #1d2327;\n 50 | }\n 51 | \n 52 | /* Active Tab Highlight */\n 53 | \n 54 | .dynos-settings-tabs .components-tab-panel__tabs .components-button.active-tab {\n 55 | \tbackground: #fff;\n 56 | \tborder: 1px solid #c3c4c7;\n 57 | \tborder-bottom: 1px solid #fff;\n 58 | \n 59 | \t/* Blend with content area */\n 60 | \tcolor: #1d2327;\n 61 | \tfont-weight: 600;\n 62 | \tz-index: 10;\n 63 | }\n 64 | \n 65 | /* Tab Icon Spacing */\n 66 | \n 67 | .dynos-settings-tabs .dashicon {\n 68 | \tmargin-inline-end: 6px;\n 69 | }\n 70 | \n 71 | /**\n 72 |  * Dynamic Online Services - Admin Settings Page Styles\n 73 |  * Modern 2025 UI Design System\n 74 |  */\n 75 | \n 76 | /* ============================================\n 77 |    CSS CUSTOM PROPERTIES - Design Tokens\n 78 |    ============================================ */\n 79 | \n 80 | :root {\n 81 | \n 82 | \t/* Primary Colors */\n 83 | \t--dynos-primary: #2563eb;\n 84 | \t--dynos-primary-dark: #1e40af;\n 85 | \t--dynos-primary-light: #3b82f6;\n 86 | \t--dynos-primary-ultra-light: #dbeafe;\n 87 | \n 88 | \t/* Secondary Colors */\n 89 | \t--dynos-secondary: #7c3aed;\n 90 | \t--dynos-secondary-dark: #6d28d9;\n 91 | \t--dynos-secondary-light: #8b5cf6;\n 92 | \n 93 | \t/* Semantic Colors */\n 94 | \t--dynos-success: #10b981;\n 95 | \t--dynos-success-bg: #d1fae5;\n 96 | \t--dynos-warning: #f59e0b;\n 97 | \t--dynos-warning-bg: #fef3c7;\n 98 | \t--dynos-danger: #ef4444;\n 99 | \t--dynos-danger-bg: #fee2e2;\n100 | \t--dynos-info: #06b6d4;\n101 | \t--dynos-info-bg: #cffafe;\n102 | \n103 | \t/* Neutral Colors */\n104 | \t--dynos-gray-50: #f9fafb;\n105 | \t--dynos-gray-100: #f3f4f6;\n106 | \t--dynos-gray-200: #e5e7eb;\n107 | \t--dynos-gray-300: #d1d5db;\n108 | \t--dynos-gray-400: #9ca3af;\n109 | \t--dynos-gray-500: #6b7280;\n110 | \t--dynos-gray-600: #4b5563;\n111 | \t--dynos-gray-700: #374151;\n112 | \t--dynos-gray-800: #1f2937;\n113 | \t--dynos-gray-900: #111827;\n114 | \n115 | \t/* Background Colors */\n116 | \t--dynos-bg-primary: #fff;\n117 | \t--dynos-bg-secondary: #f9fafb;\n118 | \t--dynos-bg-tertiary: #f3f4f6;\n119 | \n120 | \t/* Border Colors */\n121 | \t--dynos-border-light: #e5e7eb;\n122 | \t--dynos-border-medium: #d1d5db;\n123 | \t--dynos-border-dark: #9ca3af;\n124 | \n125 | \t/* Text Colors */\n126 | \t--dynos-text-primary: #111827;\n127 | \t--dynos-text-secondary: #4b5563;\n128 | \t--dynos-text-tertiary: #6b7280;\n129 | \t--dynos-text-inverse: #fff;\n130 | \n131 | \t/* Shadows */\n132 | \t--dynos-shadow-sm: 0 1px 2px 0 rgb(0 0 0 / 0.05);\n133 | \t--dynos-shadow-md: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1);\n134 | \t--dynos-shadow-lg: 0 10px 15px -3px rgb(0 0 0 / 0.1), 0 4px 6px -4px rgb(0 0 0 / 0.1);\n135 | \t--dynos-shadow-xl: 0 20px 25px -5px rgb(0 0 0 / 0.1), 0 8px 10px -6px rgb(0 0 0 / 0.1);\n136 | \n137 | \t/* Spacing Scale */\n138 | \t--dynos-space-xs: 0.25rem;\n139 | \t--dynos-space-sm: 0.5rem;\n140 | \t--dynos-space-md: 1rem;\n141 | \t--dynos-space-lg: 1.5rem;\n142 | \t--dynos-space-xl: 2rem;\n143 | \t--dynos-space-2xl: 3rem;\n144 | \t--dynos-space-3xl: 4rem;\n145 | \n146 | \t/* Border Radius */\n147 | \t--dynos-radius-sm: 0.375rem;\n148 | \t--dynos-radius-md: 0.5rem;\n149 | \t--dynos-radius-lg: 0.75rem;\n150 | \t--dynos-radius-xl: 1rem;\n151 | \t--dynos-radius-full: 9999px;\n152 | \n153 | \t/* Transitions */\n154 | \t--dynos-transition-fast: 150ms cubic-bezier(0.4, 0, 0.2, 1);\n155 | \t--dynos-transition-base: 250ms cubic-bezier(0.4, 0, 0.2, 1);\n156 | \t--dynos-transition-slow: 350ms cubic-bezier(0.4, 0, 0.2, 1);\n157 | \n158 | \t/* Typography */\n159 | \t--dynos-font-sans: -apple-system, blinkmacsystemfont, \"Segoe UI\", roboto, \"Helvetica Neue\", arial, sans-serif;\n160 | \t--dynos-font-mono: ui-monospace, sfmono-regular, \"SF Mono\", menlo, monaco, consolas, monospace;\n161 | \n162 | \t/* Font Sizes */\n163 | \t--dynos-text-xs: 0.75rem;\n164 | \t--dynos-text-sm: 0.875rem;\n165 | \t--dynos-text-base: 1rem;\n166 | \t--dynos-text-lg: 1.125rem;\n167 | \t--dynos-text-xl: 1.25rem;\n168 | \t--dynos-text-2xl: 1.5rem;\n169 | \t--dynos-text-3xl: 1.875rem;\n170 | \t--dynos-text-4xl: 2.25rem;\n171 | \n172 | \t/* Font Weights */\n173 | \t--dynos-font-normal: 400;\n174 | \t--dynos-font-medium: 500;\n175 | \t--dynos-font-semibold: 600;\n176 | \t--dynos-font-bold: 700;\n177 | \n178 | \t/* Z-Index Scale */\n179 | \t--dynos-z-base: 1;\n180 | \t--dynos-z-dropdown: 10;\n181 | \t--dynos-z-sticky: 20;\n182 | \t--dynos-z-modal: 50;\n183 | \t--dynos-z-popover: 60;\n184 | \t--dynos-z-tooltip: 70;\n185 | }\n186 | \n187 | /* ============================================\n188 |    MAIN CONTAINER\n189 |    ============================================ */\n190 | \n191 | .dynos-settings-wrap {\n192 | \tmargin-block: 0;\n193 | \tmargin-inline-start: -10px;\n194 | \tmargin-inline-end: -20px;\n195 | \tpadding: 0;\n196 | \tbackground: var(--dynos-bg-secondary);\n197 | \tmin-height: 100vh;\n198 | }\n199 | \n200 | /* ============================================\n201 |    HEADER SECTION\n202 |    ============================================ */\n203 | \n204 | .wrap.dynos-settings-wrap .dynos-header {\n205 | \tposition: sticky !important;\n206 | \ttop: 32px !important;\n207 | \n208 | \t/* WordPress admin bar height */\n209 | \tz-index: var(--dynos-z-sticky) !important;\n210 | \tbackground: linear-gradient(135deg, var(--dynos-primary) 0%, var(--dynos-primary-dark) 100%) !important;\n211 | \tcolor: var(--dynos-text-inverse) !important;\n212 | \tpadding: var(--dynos-space-xl) var(--dynos-space-2xl);\n213 | \tbox-shadow: var(--dynos-shadow-lg);\n214 | \tmargin-bottom: var(--dynos-space-xl);\n215 | \tmargin-inline-start: -20px;\n216 | \tmargin-inline-end: -20px;\n217 | \tmargin-top: -10px;\n218 | \tdisplay: flex;\n219 | \talign-items: center;\n220 | \tjustify-content: space-between;\n221 | \tflex-wrap: wrap;\n222 | \tgap: var(--dynos-space-md);\n223 | }\n224 | \n225 | .dynos-header h1 {\n226 | \tmargin: 0 !important;\n227 | \tpadding: 0 !important;\n228 | \tfont-size: var(--dynos-text-3xl);\n229 | \tfont-weight: var(--dynos-font-bold);\n230 | \tcolor: var(--dynos-text-inverse) !important;\n231 | \ttext-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);\n232 | \tdisplay: flex;\n233 | \talign-items: center;\n234 | \tgap: var(--dynos-space-md);\n235 | \tline-height: 1.2;\n236 | }\n237 | \n238 | .dynos-header h1::before {\n239 | \tcontent: \"⚙️\";\n240 | \tfont-size: var(--dynos-text-4xl);\n241 | }\n242 | \n243 | .dynos-header .components-button {\n244 | \theight: auto;\n245 | \tpadding: var(--dynos-space-sm) var(--dynos-space-xl);\n246 | \tfont-size: var(--dynos-text-base);\n247 | \tfont-weight: var(--dynos-font-semibold);\n248 | \tborder-radius: var(--dynos-radius-lg);\n249 | \tbox-shadow: var(--dynos-shadow-md);\n250 | \ttransition: all var(--dynos-transition-base);\n251 | \tbackground: var(--dynos-bg-primary);\n252 | \tcolor: var(--dynos-primary);\n253 | \tborder: 2px solid transparent;\n254 | }\n255 | \n256 | .dynos-header .components-button:hover:not(:disabled) {\n257 | \tbackground: var(--dynos-gray-50);\n258 | \ttransform: translateY(-2px);\n259 | \tbox-shadow: var(--dynos-shadow-lg);\n260 | }\n261 | \n262 | .dynos-header .components-button:active:not(:disabled) {\n263 | \ttransform: translateY(0);\n264 | }\n265 | \n266 | .dynos-header .components-button:disabled {\n267 | \topacity: 0.6;\n268 | \tcursor: not-allowed;\n269 | }\n270 | \n271 | /* ============================================\n272 |    TAB NAVIGATION\n273 |    ============================================ */\n274 | \n275 | .dynos-settings-tabs {\n276 | \tbackground: var(--dynos-bg-primary);\n277 | \tmargin: 0 var(--dynos-space-xl) var(--dynos-space-xl);\n278 | \tborder-radius: var(--dynos-radius-xl);\n279 | \tbox-shadow: var(--dynos-shadow-md);\n280 | \toverflow: hidden;\n281 | }\n282 | \n283 | .dynos-settings-tabs .components-tab-panel__tabs {\n284 | \tdisplay: flex;\n285 | \tgap: 0;\n286 | \tbackground: var(--dynos-gray-50);\n287 | \tborder-bottom: 2px solid var(--dynos-border-light);\n288 | \tpadding: var(--dynos-space-sm);\n289 | \tflex-wrap: wrap;\n290 | }\n291 | \n292 | .dynos-settings-tabs .components-tab-panel__tabs-item {\n293 | \tflex: 1;\n294 | \tmin-width: 120px;\n295 | \tpadding: var(--dynos-space-md) var(--dynos-space-lg);\n296 | \tmargin: 0;\n297 | \tbackground: transparent;\n298 | \tborder: 2px solid transparent;\n299 | \tborder-radius: var(--dynos-radius-md);\n300 | \tcolor: var(--dynos-text-secondary);\n301 | \tfont-weight: var(--dynos-font-medium);\n302 | \tfont-size: var(--dynos-text-sm);\n303 | \ttransition: all var(--dynos-transition-base);\n304 | \tposition: relative;\n305 | \tdisplay: flex;\n306 | \talign-items: center;\n307 | \tjustify-content: center;\n308 | \tgap: var(--dynos-space-sm);\n309 | \tcursor: pointer;\n310 | }\n311 | \n312 | .dynos-settings-tabs .components-tab-panel__tabs-item:hover {\n313 | \tbackground: var(--dynos-bg-primary);\n314 | \tcolor: var(--dynos-text-primary);\n315 | \ttransform: translateY(-2px);\n316 | }\n317 | \n318 | .dynos-settings-tabs .components-tab-panel__tabs-item.active-tab {\n319 | \tbackground: var(--dynos-primary);\n320 | \tcolor: var(--dynos-text-inverse);\n321 | \tfont-weight: var(--dynos-font-semibold);\n322 | \tbox-shadow: var(--dynos-shadow-md);\n323 | }\n324 | \n325 | .dynos-settings-tabs .components-tab-panel__tabs-item .dashicon {\n326 | \twidth: 20px;\n327 | \theight: 20px;\n328 | \tfont-size: 20px;\n329 | }\n330 | \n331 | /* Tab Content Area */\n332 | \n333 | .dynos-settings-tabs .components-tab-panel__tab-content {\n334 | \tpadding: var(--dynos-space-2xl);\n335 | \tbackground: var(--dynos-bg-primary);\n336 | \tmin-height: 400px;\n337 | }\n338 | \n339 | /* ============================================\n340 |    TAB CONTENT\n341 |    ============================================ */\n342 | \n343 | .dynos-tab-content {\n344 | \tmax-width: 1200px;\n345 | \tmargin: 0 auto;\n346 | }\n347 | \n348 | .dynos-tab-content > h2 {\n349 | \tmargin: 0 0 var(--dynos-space-xl);\n350 | \tpadding: 0 0 var(--dynos-space-md);\n351 | \tfont-size: var(--dynos-text-2xl);\n352 | \tfont-weight: var(--dynos-font-bold);\n353 | \tcolor: var(--dynos-text-primary);\n354 | \tborder-bottom: 3px solid var(--dynos-primary);\n355 | \tdisplay: inline-block;\n356 | }\n357 | \n358 | /* ============================================\n359 |    PANEL BODY (Cards)\n360 |    ============================================ */\n361 | \n362 | .dynos-tab-content .components-panel__body {\n363 | \tbackground: var(--dynos-bg-primary);\n364 | \tborder: 1px solid var(--dynos-border-light);\n365 | \tborder-radius: var(--dynos-radius-lg);\n366 | \tmargin-bottom: var(--dynos-space-lg);\n367 | \tbox-shadow: var(--dynos-shadow-sm);\n368 | \ttransition: all var(--dynos-transition-base);\n369 | \toverflow: hidden;\n370 | }\n371 | \n372 | .dynos-tab-content .components-panel__body:hover {\n373 | \tbox-shadow: var(--dynos-shadow-md);\n374 | \tborder-color: var(--dynos-primary-light);\n375 | }\n376 | \n377 | .dynos-tab-content .components-panel__body-title {\n378 | \tbackground: linear-gradient(to right, var(--dynos-gray-50), var(--dynos-bg-primary));\n379 | \tborder-bottom: 1px solid var(--dynos-border-light);\n380 | \tpadding: var(--dynos-space-lg);\n381 | \tmargin: 0;\n382 | }\n383 | \n384 | .dynos-tab-content .components-panel__body-title button {\n385 | \tfont-size: var(--dynos-text-lg);\n386 | \tfont-weight: var(--dynos-font-semibold);\n387 | \tcolor: var(--dynos-text-primary);\n388 | \tpadding: 0;\n389 | \tdisplay: flex;\n390 | \talign-items: center;\n391 | \tgap: var(--dynos-space-sm);\n392 | \twidth: 100%;\n393 | \ttransition: color var(--dynos-transition-fast);\n394 | }\n395 | \n396 | .dynos-tab-content .components-panel__body-title button:hover {\n397 | \tcolor: var(--dynos-primary);\n398 | }\n399 | \n400 | .dynos-tab-content .components-panel__body-title .components-panel__arrow {\n401 | \twidth: 24px;\n402 | \theight: 24px;\n403 | \ttransition: transform var(--dynos-transition-base);\n404 | }\n405 | \n406 | .dynos-tab-content .components-panel__body.is-opened .components-panel__body-title .components-panel__arrow {\n407 | \ttransform: rotate(0deg);\n408 | }\n409 | \n410 | .dynos-tab-content .components-panel__body:not(.is-opened) .components-panel__body-title .components-panel__arrow {\n411 | \ttransform: rotate(-90deg);\n412 | }\n413 | \n414 | .dynos-tab-content .components-panel__body-toggle {\n415 | \tpadding: 0;\n416 | }\n417 | \n418 | /* Panel Content */\n419 | \n420 | .dynos-tab-content .components-panel__body .components-panel__body-toggle + div {\n421 | \tpadding: var(--dynos-space-xl);\n422 | \tbackground: var(--dynos-bg-primary);\n423 | }\n424 | \n425 | /* ============================================\n426 |    FORM CONTROLS\n427 |    ============================================ */\n428 | \n429 | .dynos-tab-content .components-base-control {\n430 | \tmargin-bottom: var(--dynos-space-xl);\n431 | }\n432 | \n433 | .dynos-tab-content .components-base-control:last-child {\n434 | \tmargin-bottom: 0;\n435 | }\n436 | \n437 | .dynos-tab-content .components-base-control__label {\n438 | \tfont-size: var(--dynos-text-sm);\n439 | \tfont-weight: var(--dynos-font-semibold);\n440 | \tcolor: var(--dynos-text-primary);\n441 | \tmargin-bottom: var(--dynos-space-sm);\n442 | \tdisplay: block;\n443 | }\n444 | \n445 | .dynos-tab-content .components-base-control__help {\n446 | \tfont-size: var(--dynos-text-xs);\n447 | \tcolor: var(--dynos-text-tertiary);\n448 | \tmargin-top: var(--dynos-space-xs);\n449 | \tfont-style: italic;\n450 | }\n451 | \n452 | /* Text Inputs */\n453 | \n454 | .dynos-tab-content .components-text-control__input,\n455 | .dynos-tab-content .components-textarea-control__input {\n456 | \tborder: 2px solid var(--dynos-border-light);\n457 | \tborder-radius: var(--dynos-radius-md);\n458 | \tpadding: var(--dynos-space-sm) var(--dynos-space-md);\n459 | \tfont-size: var(--dynos-text-sm);\n460 | \ttransition: all var(--dynos-transition-base);\n461 | \twidth: 100%;\n462 | \tmax-width: 500px;\n463 | }\n464 | \n465 | .dynos-tab-content .components-text-control__input:focus,\n466 | .dynos-tab-content .components-textarea-control__input:focus {\n467 | \tborder-color: var(--dynos-primary);\n468 | \tbox-shadow: 0 0 0 3px var(--dynos-primary-ultra-light);\n469 | \toutline: none;\n470 | }\n471 | \n472 | /* Select Controls */\n473 | \n474 | .dynos-tab-content .components-select-control__input {\n475 | \tborder: 2px solid var(--dynos-border-light);\n476 | \tborder-radius: var(--dynos-radius-md);\n477 | \tpadding: var(--dynos-space-sm) var(--dynos-space-md);\n478 | \tfont-size: var(--dynos-text-sm);\n479 | \ttransition: all var(--dynos-transition-base);\n480 | \tmax-width: 500px;\n481 | }\n482 | \n483 | .dynos-tab-content .components-select-control__input:focus {\n484 | \tborder-color: var(--dynos-primary);\n485 | \tbox-shadow: 0 0 0 3px var(--dynos-primary-ultra-light);\n486 | \toutline: none;\n487 | }\n488 | \n489 | /* Toggle Controls */\n490 | \n491 | .dynos-tab-content .components-toggle-control {\n492 | \tdisplay: flex;\n493 | \talign-items: center;\n494 | \tgap: var(--dynos-space-md);\n495 | }\n496 | \n497 | .dynos-tab-content .components-form-toggle {\n498 | \tmargin: 0;\n499 | }\n500 | \n501 | .dynos-tab-content .components-form-toggle.is-checked .components-form-toggle__track {\n502 | \tbackground-color: var(--dynos-primary);\n503 | }\n504 | \n505 | /* Checkbox Controls */\n506 | \n507 | .dynos-tab-content .components-checkbox-control__input[type=\"checkbox\"] {\n508 | \twidth: 20px;\n509 | \theight: 20px;\n510 | \tborder: 2px solid var(--dynos-border-medium);\n511 | \tborder-radius: var(--dynos-radius-sm);\n512 | \ttransition: all var(--dynos-transition-fast);\n513 | }\n514 | \n515 | .dynos-tab-content .components-checkbox-control__input[type=\"checkbox\"]:checked {\n516 | \tbackground-color: var(--dynos-primary);\n517 | \tborder-color: var(--dynos-primary);\n518 | }\n519 | \n520 | /* Color Picker */\n521 | \n522 | .dynos-tab-content .components-color-picker {\n523 | \tborder-radius: var(--dynos-radius-md);\n524 | \toverflow: hidden;\n525 | }\n526 | \n527 | /* Range Control */\n528 | \n529 | .dynos-tab-content .components-range-control {\n530 | \tmax-width: 500px;\n531 | }\n532 | \n533 | .dynos-tab-content .components-range-control__slider {\n534 | \taccent-color: var(--dynos-primary);\n535 | }\n536 | \n537 | .dynos-tab-content .components-range-control__number {\n538 | \tborder: 2px solid var(--dynos-border-light);\n539 | \tborder-radius: var(--dynos-radius-md);\n540 | \tpadding: var(--dynos-space-xs) var(--dynos-space-sm);\n541 | \tfont-size: var(--dynos-text-sm);\n542 | }\n543 | \n544 | .dynos-tab-content .components-range-control__number:focus {\n545 | \tborder-color: var(--dynos-primary);\n546 | \toutline: none;\n547 | }\n548 | \n549 | /* ============================================\n550 |    SECTION DIVIDERS\n551 |    ============================================ */\n552 | \n553 | .dynos-tab-content hr {\n554 | \tborder: none;\n555 | \tborder-top: 2px solid var(--dynos-border-light);\n556 | \tmargin: var(--dynos-space-xl) 0;\n557 | }\n558 | \n559 | .dynos-tab-content h3 {\n560 | \tfont-size: var(--dynos-text-lg);\n561 | \tfont-weight: var(--dynos-font-semibold);\n562 | \tcolor: var(--dynos-text-primary);\n563 | \tmargin: var(--dynos-space-lg) 0 var(--dynos-space-md);\n564 | \tpadding-inline-start: var(--dynos-space-md);\n565 | \tborder-inline-start: 4px solid var(--dynos-primary);\n566 | }\n567 | \n568 | /* ============================================\n569 |    SPECIAL COMPONENTS\n570 |    ============================================ */\n571 | \n572 | /* Warning/Danger Boxes */\n573 | \n574 | .dynos-warning-box {\n575 | \tpadding: var(--dynos-space-lg);\n576 | \tborder: 2px solid var(--dynos-danger);\n577 | \tborder-radius: var(--dynos-radius-lg);\n578 | \tbackground: var(--dynos-danger-bg);\n579 | \tmargin: var(--dynos-space-md) 0;\n580 | }\n581 | \n582 | .dynos-warning-box p {\n583 | \tcolor: var(--dynos-danger);\n584 | \tmargin: var(--dynos-space-sm) 0 0;\n585 | \tfont-weight: var(--dynos-font-medium);\n586 | \tfont-style: italic;\n587 | }\n588 | \n589 | /* Info Boxes */\n590 | \n591 | .dynos-info-box {\n592 | \tpadding: var(--dynos-space-lg);\n593 | \tborder: 2px solid var(--dynos-info);\n594 | \tborder-radius: var(--dynos-radius-lg);\n595 | \tbackground: var(--dynos-info-bg);\n596 | \tmargin: var(--dynos-space-md) 0;\n597 | \tfont-size: var(--dynos-text-sm);\n598 | \tcolor: var(--dynos-gray-700);\n599 | }\n600 | \n601 | /* ============================================\n602 |    NOTIFICATIONS (Snackbar)\n603 |    ============================================ */\n604 | \n605 | .components-snackbar-list {\n606 | \tposition: fixed;\n607 | \tbottom: var(--dynos-space-xl);\n608 | \tright: var(--dynos-space-xl);\n609 | \tz-index: var(--dynos-z-modal);\n610 | }\n611 | \n612 | .components-snackbar {\n613 | \tborder-radius: var(--dynos-radius-lg);\n614 | \tbox-shadow: var(--dynos-shadow-xl);\n615 | \tpadding: var(--dynos-space-md) var(--dynos-space-lg);\n616 | \tfont-size: var(--dynos-text-sm);\n617 | \tfont-weight: var(--dynos-font-medium);\n618 | \tmin-width: 300px;\n619 | }\n620 | \n621 | .components-snackbar.components-snackbar--success {\n622 | \tbackground: var(--dynos-success);\n623 | }\n624 | \n625 | .components-snackbar.components-snackbar--error {\n626 | \tbackground: var(--dynos-danger);\n627 | }\n628 | \n629 | /* ============================================\n630 |    RESPONSIVE DESIGN\n631 |    ============================================ */\n632 | \n633 | /* Tablet and below */\n634 | \n635 | @media screen and (max-width: 782px) {\n636 | \n637 | \t.dynos-header {\n638 | \t\ttop: 46px;\n639 | \n640 | \t\t/* WordPress mobile admin bar height */\n641 | \t\tpadding: var(--dynos-space-lg) var(--dynos-space-lg);\n642 | \t\tflex-direction: column;\n643 | \t\talign-items: stretch;\n644 | \t}\n645 | \n646 | \t.dynos-header h1 {\n647 | \t\tfont-size: var(--dynos-text-2xl);\n648 | \t\ttext-align: center;\n649 | \t\tjustify-content: center;\n650 | \t}\n651 | \n652 | \t.dynos-header .components-button {\n653 | \t\twidth: 100%;\n654 | \t\tjustify-content: center;\n655 | \t}\n656 | \n657 | \t.dynos-settings-tabs {\n658 | \t\tmargin: 0 var(--dynos-space-md) var(--dynos-space-md);\n659 | \t\tborder-radius: var(--dynos-radius-lg);\n660 | \t}\n661 | \n662 | \t.dynos-settings-tabs .components-tab-panel__tabs {\n663 | \t\tflex-direction: column;\n664 | \t\tpadding: var(--dynos-space-xs);\n665 | \t}\n666 | \n667 | \t.dynos-settings-tabs .components-tab-panel__tabs-item {\n668 | \t\twidth: 100%;\n669 | \t\tmin-width: unset;\n670 | \t}\n671 | \n672 | \t.dynos-settings-tabs .components-tab-panel__tab-content {\n673 | \t\tpadding: var(--dynos-space-lg);\n674 | \t}\n675 | \n676 | \t.dynos-tab-content {\n677 | \t\tpadding: 0;\n678 | \t}\n679 | \n680 | \t.dynos-tab-content .components-text-control__input,\n681 | \t.dynos-tab-content .components-textarea-control__input,\n682 | \t.dynos-tab-content .components-select-control__input,\n683 | \t.dynos-tab-content .components-range-control {\n684 | \t\tmax-width: 100%;\n685 | \t}\n686 | }\n687 | \n688 | /* Mobile */\n689 | \n690 | @media screen and (max-width: 600px) {\n691 | \n692 | \t.dynos-settings-wrap {\n693 | \t\tmargin: 0;\n694 | \t}\n695 | \n696 | \t.dynos-header h1 {\n697 | \t\tfont-size: var(--dynos-text-xl);\n698 | \t}\n699 | \n700 | \t.dynos-header h1::before {\n701 | \t\tfont-size: var(--dynos-text-2xl);\n702 | \t}\n703 | \n704 | \t.dynos-settings-tabs {\n705 | \t\tmargin: 0 0 var(--dynos-space-md);\n706 | \t\tborder-radius: 0;\n707 | \t}\n708 | \n709 | \t.dynos-settings-tabs .components-tab-panel__tab-content {\n710 | \t\tpadding: var(--dynos-space-md);\n711 | \t}\n712 | \n713 | \t.dynos-tab-content .components-panel__body .components-panel__body-toggle + div {\n714 | \t\tpadding: var(--dynos-space-lg);\n715 | \t}\n716 | \n717 | \t.components-snackbar-list {\n718 | \t\tbottom: var(--dynos-space-md);\n719 | \t\tright: var(--dynos-space-md);\n720 | \t\tleft: var(--dynos-space-md);\n721 | \t}\n722 | \n723 | \t.components-snackbar {\n724 | \t\tmin-width: unset;\n725 | \t\twidth: 100%;\n726 | \t}\n727 | }\n728 | \n729 | /* ============================================\n730 |    LOADING STATE\n731 |    ============================================ */\n732 | \n733 | .dynos-loading {\n734 | \tdisplay: flex;\n735 | \talign-items: center;\n736 | \tjustify-content: center;\n737 | \tmin-height: 400px;\n738 | \tbackground: var(--dynos-bg-secondary);\n739 | }\n740 | \n741 | .dynos-loading .components-spinner {\n742 | \twidth: 48px;\n743 | \theight: 48px;\n744 | \tcolor: var(--dynos-primary);\n745 | }\n746 | \n747 | /* ============================================\n748 |    ANIMATIONS\n749 |    ============================================ */\n750 | \n751 | @keyframes dynos-fade-in {\n752 | \n753 | \tfrom {\n754 | \t\topacity: 0;\n755 | \t\ttransform: translateY(10px);\n756 | \t}\n757 | \n758 | \tto {\n759 | \t\topacity: 1;\n760 | \t\ttransform: translateY(0);\n761 | \t}\n762 | }\n763 | \n764 | .dynos-tab-content .components-panel__body {\n765 | \tanimation: dynos-fade-in var(--dynos-transition-base);\n766 | }\n767 | \n768 | /* Reduce motion for accessibility */\n769 | \n770 | @media (prefers-reduced-motion: reduce) {\n771 | \n772 | \t*,\n773 | \t*::before,\n774 | \t*::after {\n775 | \t\tanimation-duration: 0.01ms !important;\n776 | \t\tanimation-iteration-count: 1 !important;\n777 | \t\ttransition-duration: 0.01ms !important;\n778 | \t}\n779 | }\n780 | \n781 | /* ============================================\n782 |    ACCESSIBILITY ENHANCEMENTS\n783 |    ============================================ */\n784 | \n785 | /* Focus visible styles */\n786 | \n787 | .dynos-settings-tabs .components-tab-panel__tabs-item:focus-visible,\n788 | .dynos-tab-content .components-text-control__input:focus-visible,\n789 | .dynos-tab-content .components-textarea-control__input:focus-visible,\n790 | .dynos-tab-content .components-select-control__input:focus-visible {\n791 | \toutline: 3px solid var(--dynos-primary);\n792 | \toutline-offset: 2px;\n793 | }\n794 | \n795 | /* High contrast mode support */\n796 | \n797 | @media (prefers-contrast: high) {\n798 | \n799 | \t.dynos-tab-content .components-panel__body {\n800 | \t\tborder-width: 2px;\n801 | \t}\n802 | \n803 | \t.dynos-settings-tabs .components-tab-panel__tabs-item.active-tab {\n804 | \t\tborder: 3px solid var(--dynos-primary-dark);\n805 | \t}\n806 | }\n807 | `, \"\",{\"version\":3,\"sources\":[\"webpack://./src/index.css\"],\"names\":[],\"mappings\":\"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\",\"sourcesContent\":[\".dynos-tab-content {\\n\\tbackground: var(--wp--preset--color--background, #fff);\\n\\tpadding: 20px;\\n\\tborder: 1px solid #c3c4c7;\\n\\tborder-top: none;\\n}\\n\\n/* Tab Navigation Styles */\\n.dynos-settings-tabs .components-tab-panel__tabs {\\n\\tdisplay: flex;\\n\\tgap: 4px;\\n\\tborder-bottom: 1px solid #c3c4c7;\\n\\tmargin-bottom: 0 !important;\\n\\n\\t/* Override default TabPanel margin */\\n\\tpadding-inline-start: 10px;\\n}\\n\\n.dynos-settings-tabs .components-tab-panel__tabs .components-button {\\n\\tbackground: #e5e5e5;\\n\\tborder: 1px solid transparent;\\n\\tborder-bottom: none;\\n\\tborder-radius: 4px 4px 0 0;\\n\\tmargin-bottom: -1px;\\n\\n\\t/* Overlap the container border */\\n\\tpadding: 10px 16px;\\n\\tfont-weight: 500;\\n\\tcolor: #50575e;\\n\\ttransition: all 0.2s ease;\\n}\\n\\n.dynos-settings-tabs .components-tab-panel__tabs .components-button:hover {\\n\\tbackground: #fff;\\n\\tcolor: #1d2327;\\n}\\n\\n/* Active Tab Highlight */\\n.dynos-settings-tabs .components-tab-panel__tabs .components-button.active-tab {\\n\\tbackground: #fff;\\n\\tborder: 1px solid #c3c4c7;\\n\\tborder-bottom: 1px solid #fff;\\n\\n\\t/* Blend with content area */\\n\\tcolor: #1d2327;\\n\\tfont-weight: 600;\\n\\tz-index: 10;\\n}\\n\\n/* Tab Icon Spacing */\\n.dynos-settings-tabs .dashicon {\\n\\tmargin-inline-end: 6px;\\n}\\n\\n/**\\n * Dynamic Online Services - Admin Settings Page Styles\\n * Modern 2025 UI Design System\\n */\\n\\n/* ============================================\\n   CSS CUSTOM PROPERTIES - Design Tokens\\n   ============================================ */\\n\\n:root {\\n\\n\\t/* Primary Colors */\\n\\t--dynos-primary: #2563eb;\\n\\t--dynos-primary-dark: #1e40af;\\n\\t--dynos-primary-light: #3b82f6;\\n\\t--dynos-primary-ultra-light: #dbeafe;\\n\\n\\t/* Secondary Colors */\\n\\t--dynos-secondary: #7c3aed;\\n\\t--dynos-secondary-dark: #6d28d9;\\n\\t--dynos-secondary-light: #8b5cf6;\\n\\n\\t/* Semantic Colors */\\n\\t--dynos-success: #10b981;\\n\\t--dynos-success-bg: #d1fae5;\\n\\t--dynos-warning: #f59e0b;\\n\\t--dynos-warning-bg: #fef3c7;\\n\\t--dynos-danger: #ef4444;\\n\\t--dynos-danger-bg: #fee2e2;\\n\\t--dynos-info: #06b6d4;\\n\\t--dynos-info-bg: #cffafe;\\n\\n\\t/* Neutral Colors */\\n\\t--dynos-gray-50: #f9fafb;\\n\\t--dynos-gray-100: #f3f4f6;\\n\\t--dynos-gray-200: #e5e7eb;\\n\\t--dynos-gray-300: #d1d5db;\\n\\t--dynos-gray-400: #9ca3af;\\n\\t--dynos-gray-500: #6b7280;\\n\\t--dynos-gray-600: #4b5563;\\n\\t--dynos-gray-700: #374151;\\n\\t--dynos-gray-800: #1f2937;\\n\\t--dynos-gray-900: #111827;\\n\\n\\t/* Background Colors */\\n\\t--dynos-bg-primary: #fff;\\n\\t--dynos-bg-secondary: #f9fafb;\\n\\t--dynos-bg-tertiary: #f3f4f6;\\n\\n\\t/* Border Colors */\\n\\t--dynos-border-light: #e5e7eb;\\n\\t--dynos-border-medium: #d1d5db;\\n\\t--dynos-border-dark: #9ca3af;\\n\\n\\t/* Text Colors */\\n\\t--dynos-text-primary: #111827;\\n\\t--dynos-text-secondary: #4b5563;\\n\\t--dynos-text-tertiary: #6b7280;\\n\\t--dynos-text-inverse: #fff;\\n\\n\\t/* Shadows */\\n\\t--dynos-shadow-sm: 0 1px 2px 0 rgb(0 0 0 / 0.05);\\n\\t--dynos-shadow-md: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1);\\n\\t--dynos-shadow-lg: 0 10px 15px -3px rgb(0 0 0 / 0.1), 0 4px 6px -4px rgb(0 0 0 / 0.1);\\n\\t--dynos-shadow-xl: 0 20px 25px -5px rgb(0 0 0 / 0.1), 0 8px 10px -6px rgb(0 0 0 / 0.1);\\n\\n\\t/* Spacing Scale */\\n\\t--dynos-space-xs: 0.25rem;\\n\\t--dynos-space-sm: 0.5rem;\\n\\t--dynos-space-md: 1rem;\\n\\t--dynos-space-lg: 1.5rem;\\n\\t--dynos-space-xl: 2rem;\\n\\t--dynos-space-2xl: 3rem;\\n\\t--dynos-space-3xl: 4rem;\\n\\n\\t/* Border Radius */\\n\\t--dynos-radius-sm: 0.375rem;\\n\\t--dynos-radius-md: 0.5rem;\\n\\t--dynos-radius-lg: 0.75rem;\\n\\t--dynos-radius-xl: 1rem;\\n\\t--dynos-radius-full: 9999px;\\n\\n\\t/* Transitions */\\n\\t--dynos-transition-fast: 150ms cubic-bezier(0.4, 0, 0.2, 1);\\n\\t--dynos-transition-base: 250ms cubic-bezier(0.4, 0, 0.2, 1);\\n\\t--dynos-transition-slow: 350ms cubic-bezier(0.4, 0, 0.2, 1);\\n\\n\\t/* Typography */\\n\\t--dynos-font-sans: -apple-system, blinkmacsystemfont, \\\"Segoe UI\\\", roboto, \\\"Helvetica Neue\\\", arial, sans-serif;\\n\\t--dynos-font-mono: ui-monospace, sfmono-regular, \\\"SF Mono\\\", menlo, monaco, consolas, monospace;\\n\\n\\t/* Font Sizes */\\n\\t--dynos-text-xs: 0.75rem;\\n\\t--dynos-text-sm: 0.875rem;\\n\\t--dynos-text-base: 1rem;\\n\\t--dynos-text-lg: 1.125rem;\\n\\t--dynos-text-xl: 1.25rem;\\n\\t--dynos-text-2xl: 1.5rem;\\n\\t--dynos-text-3xl: 1.875rem;\\n\\t--dynos-text-4xl: 2.25rem;\\n\\n\\t/* Font Weights */\\n\\t--dynos-font-normal: 400;\\n\\t--dynos-font-medium: 500;\\n\\t--dynos-font-semibold: 600;\\n\\t--dynos-font-bold: 700;\\n\\n\\t/* Z-Index Scale */\\n\\t--dynos-z-base: 1;\\n\\t--dynos-z-dropdown: 10;\\n\\t--dynos-z-sticky: 20;\\n\\t--dynos-z-modal: 50;\\n\\t--dynos-z-popover: 60;\\n\\t--dynos-z-tooltip: 70;\\n}\\n\\n/* ============================================\\n   MAIN CONTAINER\\n   ============================================ */\\n\\n.dynos-settings-wrap {\\n\\tmargin-block: 0;\\n\\tmargin-inline-start: -10px;\\n\\tmargin-inline-end: -20px;\\n\\tpadding: 0;\\n\\tbackground: var(--dynos-bg-secondary);\\n\\tmin-height: 100vh;\\n}\\n\\n/* ============================================\\n   HEADER SECTION\\n   ============================================ */\\n\\n.wrap.dynos-settings-wrap .dynos-header {\\n\\tposition: sticky !important;\\n\\ttop: 32px !important;\\n\\n\\t/* WordPress admin bar height */\\n\\tz-index: var(--dynos-z-sticky) !important;\\n\\tbackground: linear-gradient(135deg, var(--dynos-primary) 0%, var(--dynos-primary-dark) 100%) !important;\\n\\tcolor: var(--dynos-text-inverse) !important;\\n\\tpadding: var(--dynos-space-xl) var(--dynos-space-2xl);\\n\\tbox-shadow: var(--dynos-shadow-lg);\\n\\tmargin-bottom: var(--dynos-space-xl);\\n\\tmargin-inline-start: -20px;\\n\\tmargin-inline-end: -20px;\\n\\tmargin-top: -10px;\\n\\tdisplay: flex;\\n\\talign-items: center;\\n\\tjustify-content: space-between;\\n\\tflex-wrap: wrap;\\n\\tgap: var(--dynos-space-md);\\n}\\n\\n.dynos-header h1 {\\n\\tmargin: 0 !important;\\n\\tpadding: 0 !important;\\n\\tfont-size: var(--dynos-text-3xl);\\n\\tfont-weight: var(--dynos-font-bold);\\n\\tcolor: var(--dynos-text-inverse) !important;\\n\\ttext-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);\\n\\tdisplay: flex;\\n\\talign-items: center;\\n\\tgap: var(--dynos-space-md);\\n\\tline-height: 1.2;\\n}\\n\\n.dynos-header h1::before {\\n\\tcontent: \\\"⚙️\\\";\\n\\tfont-size: var(--dynos-text-4xl);\\n}\\n\\n.dynos-header .components-button {\\n\\theight: auto;\\n\\tpadding: var(--dynos-space-sm) var(--dynos-space-xl);\\n\\tfont-size: var(--dynos-text-base);\\n\\tfont-weight: var(--dynos-font-semibold);\\n\\tborder-radius: var(--dynos-radius-lg);\\n\\tbox-shadow: var(--dynos-shadow-md);\\n\\ttransition: all var(--dynos-transition-base);\\n\\tbackground: var(--dynos-bg-primary);\\n\\tcolor: var(--dynos-primary);\\n\\tborder: 2px solid transparent;\\n}\\n\\n.dynos-header .components-button:hover:not(:disabled) {\\n\\tbackground: var(--dynos-gray-50);\\n\\ttransform: translateY(-2px);\\n\\tbox-shadow: var(--dynos-shadow-lg);\\n}\\n\\n.dynos-header .components-button:active:not(:disabled) {\\n\\ttransform: translateY(0);\\n}\\n\\n.dynos-header .components-button:disabled {\\n\\topacity: 0.6;\\n\\tcursor: not-allowed;\\n}\\n\\n/* ============================================\\n   TAB NAVIGATION\\n   ============================================ */\\n\\n.dynos-settings-tabs {\\n\\tbackground: var(--dynos-bg-primary);\\n\\tmargin: 0 var(--dynos-space-xl) var(--dynos-space-xl);\\n\\tborder-radius: var(--dynos-radius-xl);\\n\\tbox-shadow: var(--dynos-shadow-md);\\n\\toverflow: hidden;\\n}\\n\\n.dynos-settings-tabs .components-tab-panel__tabs {\\n\\tdisplay: flex;\\n\\tgap: 0;\\n\\tbackground: var(--dynos-gray-50);\\n\\tborder-bottom: 2px solid var(--dynos-border-light);\\n\\tpadding: var(--dynos-space-sm);\\n\\tflex-wrap: wrap;\\n}\\n\\n.dynos-settings-tabs .components-tab-panel__tabs-item {\\n\\tflex: 1;\\n\\tmin-width: 120px;\\n\\tpadding: var(--dynos-space-md) var(--dynos-space-lg);\\n\\tmargin: 0;\\n\\tbackground: transparent;\\n\\tborder: 2px solid transparent;\\n\\tborder-radius: var(--dynos-radius-md);\\n\\tcolor: var(--dynos-text-secondary);\\n\\tfont-weight: var(--dynos-font-medium);\\n\\tfont-size: var(--dynos-text-sm);\\n\\ttransition: all var(--dynos-transition-base);\\n\\tposition: relative;\\n\\tdisplay: flex;\\n\\talign-items: center;\\n\\tjustify-content: center;\\n\\tgap: var(--dynos-space-sm);\\n\\tcursor: pointer;\\n}\\n\\n.dynos-settings-tabs .components-tab-panel__tabs-item:hover {\\n\\tbackground: var(--dynos-bg-primary);\\n\\tcolor: var(--dynos-text-primary);\\n\\ttransform: translateY(-2px);\\n}\\n\\n.dynos-settings-tabs .components-tab-panel__tabs-item.active-tab {\\n\\tbackground: var(--dynos-primary);\\n\\tcolor: var(--dynos-text-inverse);\\n\\tfont-weight: var(--dynos-font-semibold);\\n\\tbox-shadow: var(--dynos-shadow-md);\\n}\\n\\n.dynos-settings-tabs .components-tab-panel__tabs-item .dashicon {\\n\\twidth: 20px;\\n\\theight: 20px;\\n\\tfont-size: 20px;\\n}\\n\\n/* Tab Content Area */\\n.dynos-settings-tabs .components-tab-panel__tab-content {\\n\\tpadding: var(--dynos-space-2xl);\\n\\tbackground: var(--dynos-bg-primary);\\n\\tmin-height: 400px;\\n}\\n\\n/* ============================================\\n   TAB CONTENT\\n   ============================================ */\\n\\n.dynos-tab-content {\\n\\tmax-width: 1200px;\\n\\tmargin: 0 auto;\\n}\\n\\n.dynos-tab-content > h2 {\\n\\tmargin: 0 0 var(--dynos-space-xl);\\n\\tpadding: 0 0 var(--dynos-space-md);\\n\\tfont-size: var(--dynos-text-2xl);\\n\\tfont-weight: var(--dynos-font-bold);\\n\\tcolor: var(--dynos-text-primary);\\n\\tborder-bottom: 3px solid var(--dynos-primary);\\n\\tdisplay: inline-block;\\n}\\n\\n/* ============================================\\n   PANEL BODY (Cards)\\n   ============================================ */\\n\\n.dynos-tab-content .components-panel__body {\\n\\tbackground: var(--dynos-bg-primary);\\n\\tborder: 1px solid var(--dynos-border-light);\\n\\tborder-radius: var(--dynos-radius-lg);\\n\\tmargin-bottom: var(--dynos-space-lg);\\n\\tbox-shadow: var(--dynos-shadow-sm);\\n\\ttransition: all var(--dynos-transition-base);\\n\\toverflow: hidden;\\n}\\n\\n.dynos-tab-content .components-panel__body:hover {\\n\\tbox-shadow: var(--dynos-shadow-md);\\n\\tborder-color: var(--dynos-primary-light);\\n}\\n\\n.dynos-tab-content .components-panel__body-title {\\n\\tbackground: linear-gradient(to right, var(--dynos-gray-50), var(--dynos-bg-primary));\\n\\tborder-bottom: 1px solid var(--dynos-border-light);\\n\\tpadding: var(--dynos-space-lg);\\n\\tmargin: 0;\\n}\\n\\n.dynos-tab-content .components-panel__body-title button {\\n\\tfont-size: var(--dynos-text-lg);\\n\\tfont-weight: var(--dynos-font-semibold);\\n\\tcolor: var(--dynos-text-primary);\\n\\tpadding: 0;\\n\\tdisplay: flex;\\n\\talign-items: center;\\n\\tgap: var(--dynos-space-sm);\\n\\twidth: 100%;\\n\\ttransition: color var(--dynos-transition-fast);\\n}\\n\\n.dynos-tab-content .components-panel__body-title button:hover {\\n\\tcolor: var(--dynos-primary);\\n}\\n\\n.dynos-tab-content .components-panel__body-title .components-panel__arrow {\\n\\twidth: 24px;\\n\\theight: 24px;\\n\\ttransition: transform var(--dynos-transition-base);\\n}\\n\\n.dynos-tab-content .components-panel__body.is-opened .components-panel__body-title .components-panel__arrow {\\n\\ttransform: rotate(0deg);\\n}\\n\\n.dynos-tab-content .components-panel__body:not(.is-opened) .components-panel__body-title .components-panel__arrow {\\n\\ttransform: rotate(-90deg);\\n}\\n\\n.dynos-tab-content .components-panel__body-toggle {\\n\\tpadding: 0;\\n}\\n\\n/* Panel Content */\\n.dynos-tab-content .components-panel__body .components-panel__body-toggle + div {\\n\\tpadding: var(--dynos-space-xl);\\n\\tbackground: var(--dynos-bg-primary);\\n}\\n\\n/* ============================================\\n   FORM CONTROLS\\n   ============================================ */\\n\\n.dynos-tab-content .components-base-control {\\n\\tmargin-bottom: var(--dynos-space-xl);\\n}\\n\\n.dynos-tab-content .components-base-control:last-child {\\n\\tmargin-bottom: 0;\\n}\\n\\n.dynos-tab-content .components-base-control__label {\\n\\tfont-size: var(--dynos-text-sm);\\n\\tfont-weight: var(--dynos-font-semibold);\\n\\tcolor: var(--dynos-text-primary);\\n\\tmargin-bottom: var(--dynos-space-sm);\\n\\tdisplay: block;\\n}\\n\\n.dynos-tab-content .components-base-control__help {\\n\\tfont-size: var(--dynos-text-xs);\\n\\tcolor: var(--dynos-text-tertiary);\\n\\tmargin-top: var(--dynos-space-xs);\\n\\tfont-style: italic;\\n}\\n\\n/* Text Inputs */\\n.dynos-tab-content .components-text-control__input,\\n.dynos-tab-content .components-textarea-control__input {\\n\\tborder: 2px solid var(--dynos-border-light);\\n\\tborder-radius: var(--dynos-radius-md);\\n\\tpadding: var(--dynos-space-sm) var(--dynos-space-md);\\n\\tfont-size: var(--dynos-text-sm);\\n\\ttransition: all var(--dynos-transition-base);\\n\\twidth: 100%;\\n\\tmax-width: 500px;\\n}\\n\\n.dynos-tab-content .components-text-control__input:focus,\\n.dynos-tab-content .components-textarea-control__input:focus {\\n\\tborder-color: var(--dynos-primary);\\n\\tbox-shadow: 0 0 0 3px var(--dynos-primary-ultra-light);\\n\\toutline: none;\\n}\\n\\n/* Select Controls */\\n.dynos-tab-content .components-select-control__input {\\n\\tborder: 2px solid var(--dynos-border-light);\\n\\tborder-radius: var(--dynos-radius-md);\\n\\tpadding: var(--dynos-space-sm) var(--dynos-space-md);\\n\\tfont-size: var(--dynos-text-sm);\\n\\ttransition: all var(--dynos-transition-base);\\n\\tmax-width: 500px;\\n}\\n\\n.dynos-tab-content .components-select-control__input:focus {\\n\\tborder-color: var(--dynos-primary);\\n\\tbox-shadow: 0 0 0 3px var(--dynos-primary-ultra-light);\\n\\toutline: none;\\n}\\n\\n/* Toggle Controls */\\n.dynos-tab-content .components-toggle-control {\\n\\tdisplay: flex;\\n\\talign-items: center;\\n\\tgap: var(--dynos-space-md);\\n}\\n\\n.dynos-tab-content .components-form-toggle {\\n\\tmargin: 0;\\n}\\n\\n.dynos-tab-content .components-form-toggle.is-checked .components-form-toggle__track {\\n\\tbackground-color: var(--dynos-primary);\\n}\\n\\n/* Checkbox Controls */\\n.dynos-tab-content .components-checkbox-control__input[type=\\\"checkbox\\\"] {\\n\\twidth: 20px;\\n\\theight: 20px;\\n\\tborder: 2px solid var(--dynos-border-medium);\\n\\tborder-radius: var(--dynos-radius-sm);\\n\\ttransition: all var(--dynos-transition-fast);\\n}\\n\\n.dynos-tab-content .components-checkbox-control__input[type=\\\"checkbox\\\"]:checked {\\n\\tbackground-color: var(--dynos-primary);\\n\\tborder-color: var(--dynos-primary);\\n}\\n\\n/* Color Picker */\\n.dynos-tab-content .components-color-picker {\\n\\tborder-radius: var(--dynos-radius-md);\\n\\toverflow: hidden;\\n}\\n\\n/* Range Control */\\n.dynos-tab-content .components-range-control {\\n\\tmax-width: 500px;\\n}\\n\\n.dynos-tab-content .components-range-control__slider {\\n\\taccent-color: var(--dynos-primary);\\n}\\n\\n.dynos-tab-content .components-range-control__number {\\n\\tborder: 2px solid var(--dynos-border-light);\\n\\tborder-radius: var(--dynos-radius-md);\\n\\tpadding: var(--dynos-space-xs) var(--dynos-space-sm);\\n\\tfont-size: var(--dynos-text-sm);\\n}\\n\\n.dynos-tab-content .components-range-control__number:focus {\\n\\tborder-color: var(--dynos-primary);\\n\\toutline: none;\\n}\\n\\n/* ============================================\\n   SECTION DIVIDERS\\n   ============================================ */\\n\\n.dynos-tab-content hr {\\n\\tborder: none;\\n\\tborder-top: 2px solid var(--dynos-border-light);\\n\\tmargin: var(--dynos-space-xl) 0;\\n}\\n\\n.dynos-tab-content h3 {\\n\\tfont-size: var(--dynos-text-lg);\\n\\tfont-weight: var(--dynos-font-semibold);\\n\\tcolor: var(--dynos-text-primary);\\n\\tmargin: var(--dynos-space-lg) 0 var(--dynos-space-md);\\n\\tpadding-inline-start: var(--dynos-space-md);\\n\\tborder-inline-start: 4px solid var(--dynos-primary);\\n}\\n\\n/* ============================================\\n   SPECIAL COMPONENTS\\n   ============================================ */\\n\\n/* Warning/Danger Boxes */\\n.dynos-warning-box {\\n\\tpadding: var(--dynos-space-lg);\\n\\tborder: 2px solid var(--dynos-danger);\\n\\tborder-radius: var(--dynos-radius-lg);\\n\\tbackground: var(--dynos-danger-bg);\\n\\tmargin: var(--dynos-space-md) 0;\\n}\\n\\n.dynos-warning-box p {\\n\\tcolor: var(--dynos-danger);\\n\\tmargin: var(--dynos-space-sm) 0 0;\\n\\tfont-weight: var(--dynos-font-medium);\\n\\tfont-style: italic;\\n}\\n\\n/* Info Boxes */\\n.dynos-info-box {\\n\\tpadding: var(--dynos-space-lg);\\n\\tborder: 2px solid var(--dynos-info);\\n\\tborder-radius: var(--dynos-radius-lg);\\n\\tbackground: var(--dynos-info-bg);\\n\\tmargin: var(--dynos-space-md) 0;\\n\\tfont-size: var(--dynos-text-sm);\\n\\tcolor: var(--dynos-gray-700);\\n}\\n\\n/* ============================================\\n   NOTIFICATIONS (Snackbar)\\n   ============================================ */\\n\\n.components-snackbar-list {\\n\\tposition: fixed;\\n\\tbottom: var(--dynos-space-xl);\\n\\tright: var(--dynos-space-xl);\\n\\tz-index: var(--dynos-z-modal);\\n}\\n\\n.components-snackbar {\\n\\tborder-radius: var(--dynos-radius-lg);\\n\\tbox-shadow: var(--dynos-shadow-xl);\\n\\tpadding: var(--dynos-space-md) var(--dynos-space-lg);\\n\\tfont-size: var(--dynos-text-sm);\\n\\tfont-weight: var(--dynos-font-medium);\\n\\tmin-width: 300px;\\n}\\n\\n.components-snackbar.components-snackbar--success {\\n\\tbackground: var(--dynos-success);\\n}\\n\\n.components-snackbar.components-snackbar--error {\\n\\tbackground: var(--dynos-danger);\\n}\\n\\n/* ============================================\\n   RESPONSIVE DESIGN\\n   ============================================ */\\n\\n/* Tablet and below */\\n@media screen and (max-width: 782px) {\\n\\n\\t.dynos-header {\\n\\t\\ttop: 46px;\\n\\n\\t\\t/* WordPress mobile admin bar height */\\n\\t\\tpadding: var(--dynos-space-lg) var(--dynos-space-lg);\\n\\t\\tflex-direction: column;\\n\\t\\talign-items: stretch;\\n\\t}\\n\\n\\t.dynos-header h1 {\\n\\t\\tfont-size: var(--dynos-text-2xl);\\n\\t\\ttext-align: center;\\n\\t\\tjustify-content: center;\\n\\t}\\n\\n\\t.dynos-header .components-button {\\n\\t\\twidth: 100%;\\n\\t\\tjustify-content: center;\\n\\t}\\n\\n\\t.dynos-settings-tabs {\\n\\t\\tmargin: 0 var(--dynos-space-md) var(--dynos-space-md);\\n\\t\\tborder-radius: var(--dynos-radius-lg);\\n\\t}\\n\\n\\t.dynos-settings-tabs .components-tab-panel__tabs {\\n\\t\\tflex-direction: column;\\n\\t\\tpadding: var(--dynos-space-xs);\\n\\t}\\n\\n\\t.dynos-settings-tabs .components-tab-panel__tabs-item {\\n\\t\\twidth: 100%;\\n\\t\\tmin-width: unset;\\n\\t}\\n\\n\\t.dynos-settings-tabs .components-tab-panel__tab-content {\\n\\t\\tpadding: var(--dynos-space-lg);\\n\\t}\\n\\n\\t.dynos-tab-content {\\n\\t\\tpadding: 0;\\n\\t}\\n\\n\\t.dynos-tab-content .components-text-control__input,\\n\\t.dynos-tab-content .components-textarea-control__input,\\n\\t.dynos-tab-content .components-select-control__input,\\n\\t.dynos-tab-content .components-range-control {\\n\\t\\tmax-width: 100%;\\n\\t}\\n}\\n\\n/* Mobile */\\n@media screen and (max-width: 600px) {\\n\\n\\t.dynos-settings-wrap {\\n\\t\\tmargin: 0;\\n\\t}\\n\\n\\t.dynos-header h1 {\\n\\t\\tfont-size: var(--dynos-text-xl);\\n\\t}\\n\\n\\t.dynos-header h1::before {\\n\\t\\tfont-size: var(--dynos-text-2xl);\\n\\t}\\n\\n\\t.dynos-settings-tabs {\\n\\t\\tmargin: 0 0 var(--dynos-space-md);\\n\\t\\tborder-radius: 0;\\n\\t}\\n\\n\\t.dynos-settings-tabs .components-tab-panel__tab-content {\\n\\t\\tpadding: var(--dynos-space-md);\\n\\t}\\n\\n\\t.dynos-tab-content .components-panel__body .components-panel__body-toggle + div {\\n\\t\\tpadding: var(--dynos-space-lg);\\n\\t}\\n\\n\\t.components-snackbar-list {\\n\\t\\tbottom: var(--dynos-space-md);\\n\\t\\tright: var(--dynos-space-md);\\n\\t\\tleft: var(--dynos-space-md);\\n\\t}\\n\\n\\t.components-snackbar {\\n\\t\\tmin-width: unset;\\n\\t\\twidth: 100%;\\n\\t}\\n}\\n\\n/* ============================================\\n   LOADING STATE\\n   ============================================ */\\n\\n.dynos-loading {\\n\\tdisplay: flex;\\n\\talign-items: center;\\n\\tjustify-content: center;\\n\\tmin-height: 400px;\\n\\tbackground: var(--dynos-bg-secondary);\\n}\\n\\n.dynos-loading .components-spinner {\\n\\twidth: 48px;\\n\\theight: 48px;\\n\\tcolor: var(--dynos-primary);\\n}\\n\\n/* ============================================\\n   ANIMATIONS\\n   ============================================ */\\n\\n@keyframes dynos-fade-in {\\n\\n\\tfrom {\\n\\t\\topacity: 0;\\n\\t\\ttransform: translateY(10px);\\n\\t}\\n\\n\\tto {\\n\\t\\topacity: 1;\\n\\t\\ttransform: translateY(0);\\n\\t}\\n}\\n\\n.dynos-tab-content .components-panel__body {\\n\\tanimation: dynos-fade-in var(--dynos-transition-base);\\n}\\n\\n/* Reduce motion for accessibility */\\n@media (prefers-reduced-motion: reduce) {\\n\\n\\t*,\\n\\t*::before,\\n\\t*::after {\\n\\t\\tanimation-duration: 0.01ms !important;\\n\\t\\tanimation-iteration-count: 1 !important;\\n\\t\\ttransition-duration: 0.01ms !important;\\n\\t}\\n}\\n\\n/* ============================================\\n   ACCESSIBILITY ENHANCEMENTS\\n   ============================================ */\\n\\n/* Focus visible styles */\\n.dynos-settings-tabs .components-tab-panel__tabs-item:focus-visible,\\n.dynos-tab-content .components-text-control__input:focus-visible,\\n.dynos-tab-content .components-textarea-control__input:focus-visible,\\n.dynos-tab-content .components-select-control__input:focus-visible {\\n\\toutline: 3px solid var(--dynos-primary);\\n\\toutline-offset: 2px;\\n}\\n\\n/* High contrast mode support */\\n@media (prefers-contrast: high) {\\n\\n\\t.dynos-tab-content .components-panel__body {\\n\\t\\tborder-width: 2px;\\n\\t}\\n\\n\\t.dynos-settings-tabs .components-tab-panel__tabs-item.active-tab {\\n\\t\\tborder: 3px solid var(--dynos-primary-dark);\\n\\t}\\n}\\n\"],\"sourceRoot\":\"\"}]);\n808 | // Exports\n809 | /* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (___CSS_LOADER_EXPORT___);\n810 | ");

/***/ }),

/***/ "@wordpress/components":
/*!************************************!*\
  !*** external ["wp","components"] ***!
  \************************************/
/***/ ((module) => {

"use strict";
module.exports = window["wp"]["components"];

/***/ }),

/***/ "@wordpress/core-data":
/*!**********************************!*\
  !*** external ["wp","coreData"] ***!
  \**********************************/
/***/ ((module) => {

"use strict";
module.exports = window["wp"]["coreData"];

/***/ }),

/***/ "@wordpress/data":
/*!******************************!*\
  !*** external ["wp","data"] ***!
  \******************************/
/***/ ((module) => {

"use strict";
module.exports = window["wp"]["data"];

/***/ }),

/***/ "@wordpress/element":
/*!*********************************!*\
  !*** external ["wp","element"] ***!
  \*********************************/
/***/ ((module) => {

"use strict";
module.exports = window["wp"]["element"];

/***/ }),

/***/ "@wordpress/i18n":
/*!******************************!*\
  !*** external ["wp","i18n"] ***!
  \******************************/
/***/ ((module) => {

"use strict";
module.exports = window["wp"]["i18n"];

/***/ }),

/***/ "react/jsx-runtime":
/*!**********************************!*\
  !*** external "ReactJSXRuntime" ***!
  \**********************************/
/***/ ((module) => {

"use strict";
module.exports = window["ReactJSXRuntime"];

/***/ })

/******/ 	});
/************************************************************************/
/******/ 	// The module cache
/******/ 	var __webpack_module_cache__ = {};
/******/ 	
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/ 		// Check if module is in cache
/******/ 		var cachedModule = __webpack_module_cache__[moduleId];
/******/ 		if (cachedModule !== undefined) {
/******/ 			return cachedModule.exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = __webpack_module_cache__[moduleId] = {
/******/ 			// no module.id needed
/******/ 			// no module.loaded needed
/******/ 			exports: {}
/******/ 		};
/******/ 	
/******/ 		// Execute the module function
/******/ 		__webpack_modules__[moduleId](module, module.exports, __webpack_require__);
/******/ 	
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/ 	
/************************************************************************/
/******/ 	/* webpack/runtime/compat get default export */
/******/ 	(() => {
/******/ 		// getDefaultExport function for compatibility with non-harmony modules
/******/ 		__webpack_require__.n = (module) => {
/******/ 			var getter = module && module.__esModule ?
/******/ 				() => (module['default']) :
/******/ 				() => (module);
/******/ 			__webpack_require__.d(getter, { a: getter });
/******/ 			return getter;
/******/ 		};
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/define property getters */
/******/ 	(() => {
/******/ 		// define getter functions for harmony exports
/******/ 		__webpack_require__.d = (exports, definition) => {
/******/ 			for(var key in definition) {
/******/ 				if(__webpack_require__.o(definition, key) && !__webpack_require__.o(exports, key)) {
/******/ 					Object.defineProperty(exports, key, { enumerable: true, get: definition[key] });
/******/ 				}
/******/ 			}
/******/ 		};
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/hasOwnProperty shorthand */
/******/ 	(() => {
/******/ 		__webpack_require__.o = (obj, prop) => (Object.prototype.hasOwnProperty.call(obj, prop))
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/make namespace object */
/******/ 	(() => {
/******/ 		// define __esModule on exports
/******/ 		__webpack_require__.r = (exports) => {
/******/ 			if(typeof Symbol !== 'undefined' && Symbol.toStringTag) {
/******/ 				Object.defineProperty(exports, Symbol.toStringTag, { value: 'Module' });
/******/ 			}
/******/ 			Object.defineProperty(exports, '__esModule', { value: true });
/******/ 		};
/******/ 	})();
/******/ 	
/************************************************************************/
var __webpack_exports__ = {};
// This entry needs to be wrapped in an IIFE because it needs to be in strict mode.
(() => {
"use strict";
/*!**********************!*\
  !*** ./src/index.js ***!
  \**********************/
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/element */ "@wordpress/element");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _admin_SettingsApp__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./admin/SettingsApp */ "./src/admin/SettingsApp.js");
/* harmony import */ var _index_css__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./index.css */ "./src/index.css");
/* harmony import */ var react_jsx_runtime__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! react/jsx-runtime */ "react/jsx-runtime");
/* harmony import */ var react_jsx_runtime__WEBPACK_IMPORTED_MODULE_3___default = /*#__PURE__*/__webpack_require__.n(react_jsx_runtime__WEBPACK_IMPORTED_MODULE_3__);


 // Assuming we might want some styles

document.addEventListener('DOMContentLoaded', () => {
  const container = document.getElementById('dynos-settings-root');
  if (container) {
    (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.render)(/*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_3__.jsx)(_admin_SettingsApp__WEBPACK_IMPORTED_MODULE_1__["default"], {}), container);
  }
});
})();

/******/ })()
;
//# sourceMappingURL=index.js.map