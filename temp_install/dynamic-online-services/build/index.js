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
  var ReactPropTypesSecret = __webpack_require__(/*! ./lib/ReactPropTypesSecret */ "./node_modules/prop-types/lib/ReactPropTypesSecret.js");
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

var ReactPropTypesSecret = __webpack_require__(/*! ./lib/ReactPropTypesSecret */ "./node_modules/prop-types/lib/ReactPropTypesSecret.js");
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

/***/ "./node_modules/prop-types/lib/ReactPropTypesSecret.js":
/*!*************************************************************!*\
  !*** ./node_modules/prop-types/lib/ReactPropTypesSecret.js ***!
  \*************************************************************/
/***/ ((module) => {

"use strict";
/**
 * Copyright (c) 2013-present, Facebook, Inc.
 *
 * This source code is licensed under the MIT license found in the
 * LICENSE file in the root directory of this source tree.
 */



var ReactPropTypesSecret = 'SECRET_DO_NOT_PASS_THIS_OR_YOU_WILL_BE_FIRED';

module.exports = ReactPropTypesSecret;


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
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
// extracted by mini-css-extract-plugin


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
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
// extracted by mini-css-extract-plugin


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