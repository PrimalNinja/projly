/*====================================================================================
// 3rd Party Copyright ================================================================
// All parties copyrights to their respective parties where applicable.  See About for
// for more information. Some minified using Google's closure compiler
*/

/*!
 * jQuery UI Touch Punch 0.2.3
 *
 * Copyright 2011–2014, Dave Furfero
 * Dual licensed under the MIT or GPL Version 2 licenses.
 *
 * Depends:
 *  jquery.ui.widget.js
 *  jquery.ui.mouse.js
 */
(function ($) {

  // Detect touch support
  $.support.touch = 'ontouchend' in document;

  // Ignore browsers without touch support
  if (!$.support.touch) {
    return;
  }

  var mouseProto = $.ui.mouse.prototype,
      _mouseInit = mouseProto._mouseInit,
      _mouseDestroy = mouseProto._mouseDestroy,
      touchHandled;

  /**
   * Simulate a mouse event based on a corresponding touch event
   * @param {Object} event A touch event
   * @param {String} simulatedType The corresponding mouse event
   */
  function simulateMouseEvent (event, simulatedType) {

    // Ignore multi-touch events
    if (event.originalEvent.touches.length > 1) {
      return;
    }

    event.preventDefault();

    var touch = event.originalEvent.changedTouches[0],
        simulatedEvent = document.createEvent('MouseEvents');
    
    // Initialize the simulated mouse event using the touch event's coordinates
    simulatedEvent.initMouseEvent(
      simulatedType,    // type
      true,             // bubbles                    
      true,             // cancelable                 
      window,           // view                       
      1,                // detail                     
      touch.screenX,    // screenX                    
      touch.screenY,    // screenY                    
      touch.clientX,    // clientX                    
      touch.clientY,    // clientY                    
      false,            // ctrlKey                    
      false,            // altKey                     
      false,            // shiftKey                   
      false,            // metaKey                    
      0,                // button                     
      null              // relatedTarget              
    );

    // Dispatch the simulated event to the target element
    event.target.dispatchEvent(simulatedEvent);
  }

  /**
   * Handle the jQuery UI widget's touchstart events
   * @param {Object} event The widget element's touchstart event
   */
  mouseProto._touchStart = function (event) {

    var self = this;

    // Ignore the event if another widget is already being handled
    if (touchHandled || !self._mouseCapture(event.originalEvent.changedTouches[0])) {
      return;
    }

    // Set the flag to prevent other widgets from inheriting the touch event
    touchHandled = true;

    // Track movement to determine if interaction was a click
    self._touchMoved = false;

    // Simulate the mouseover event
    simulateMouseEvent(event, 'mouseover');

    // Simulate the mousemove event
    simulateMouseEvent(event, 'mousemove');

    // Simulate the mousedown event
    simulateMouseEvent(event, 'mousedown');
  };

  /**
   * Handle the jQuery UI widget's touchmove events
   * @param {Object} event The document's touchmove event
   */
  mouseProto._touchMove = function (event) {

    // Ignore event if not handled
    if (!touchHandled) {
      return;
    }

    // Interaction was not a click
    this._touchMoved = true;

    // Simulate the mousemove event
    simulateMouseEvent(event, 'mousemove');
  };

  /**
   * Handle the jQuery UI widget's touchend events
   * @param {Object} event The document's touchend event
   */
  mouseProto._touchEnd = function (event) {

    // Ignore event if not handled
    if (!touchHandled) {
      return;
    }

    // Simulate the mouseup event
    simulateMouseEvent(event, 'mouseup');

    // Simulate the mouseout event
    simulateMouseEvent(event, 'mouseout');

    // If the touch interaction did not move, it should trigger a click
    if (!this._touchMoved) {

      // Simulate the click event
      simulateMouseEvent(event, 'click');
    }

    // Unset the flag to allow other widgets to inherit the touch event
    touchHandled = false;
  };

  /**
   * A duck punch of the $.ui.mouse _mouseInit method to support touch events.
   * This method extends the widget with bound touch event handlers that
   * translate touch events to mouse events and pass them to the widget's
   * original mouse event handling methods.
   */
  mouseProto._mouseInit = function () {
    
    var self = this;

    // Delegate the touch handlers to the widget's element
    self.element.bind({
      touchstart: $.proxy(self, '_touchStart'),
      touchmove: $.proxy(self, '_touchMove'),
      touchend: $.proxy(self, '_touchEnd')
    });

    // Call the original $.ui.mouse init method
    _mouseInit.call(self);
  };

  /**
   * Remove the touch event handlers
   */
  mouseProto._mouseDestroy = function () {
    
    var self = this;

    // Delegate the touch handlers to the widget's element
    self.element.unbind({
      touchstart: $.proxy(self, '_touchStart'),
      touchmove: $.proxy(self, '_touchMove'),
      touchend: $.proxy(self, '_touchEnd')
    });

    // Call the original $.ui.mouse destroy method
    _mouseDestroy.call(self);
  };

})(jQuery);// Cyborg Wiki v20250708
// (c) 2025 Cyborg Unicorn Pty Ltd.
// This software is released under MIT License.

function cyborgWikiToHtml(strWiki_a, strInternalPageURL_a, strInternalImageURL_a, arrPlaceholders_a) 
{
	var intI;
	
	// Extract code blocks first to protect them from processing
	var arrCodeBlocks = [];
	var intCodeCounter = 0;
	var strResult = strWiki_a.replace(/<code>([\s\S]*?)<\/code>/gi, function(strMatch_a, strCode_a) 
	{
		var strPlaceholder = '~CODE_' + intCodeCounter + '~';
		arrCodeBlocks.push(strCode_a);
		intCodeCounter++;
		return strPlaceholder;
	});

	// Replace placeholders
	if (arrPlaceholders_a) 
	{
		for (intI = 0; intI < arrPlaceholders_a.length; intI++) 
		{
			var objPlaceholder = arrPlaceholders_a[intI];

			// Use a case-insensitive global match for placeholder text
			var regex = new RegExp(objPlaceholder.placeholder.toUpperCase(), 'g');
			strResult = strResult.replace(regex, objPlaceholder.value);
		}
	}

	strResult = strResult.replace(/\r/g, '');

	// Headings (match from largest to smallest to prevent partial replaces)
	// Note the order: === first, then ==, then =
	strResult = strResult.replace(/====(.*?)====/g, '<span class="gscw-subsubheading">$1</span>');
	strResult = strResult.replace(/===(.*?)===/g, '<span class="gscw-subsubheading">$1</span>');
	strResult = strResult.replace(/==(.*?)==/g, '<span class="gscw-subheading">$1</span>');
	strResult = strResult.replace(/=(.*?)=/g, '<span class="gscw-heading">$1</span>');

	// Bold text
	strResult = strResult.replace(/'''(.*?)'''/g, '<span class="gscw-bold">$1</span>');
	
	// Horizontal Rule
	strResult = strResult.replace(/- - - -/g, '<hr class="gscw-hr">');

	// Internal Images
	strResult = strResult.replace(/\[\[Image:(.*?)\|(.*?)\]\]/g, '<img class="gscw-image" src="' + strInternalImageURL_a + '$1" alt="$2"/>');

	// External Images
	strResult = strResult.replace(/\[Image:(.*?)\|(.*?)\]/g, '<img class="gscw-image" src="$1" alt="$2"/>');

	// Internal Links
	strResult = strResult.replace(/\[\[(.*?)\|(.*?)\]\]/g, '<span class="gscw-internallink gecw-internallink" wiki="$1" title="$2">$2</span>');
	//strResult = strResult.replace(/\[\[(.*?)\|(.*?)\]\]/g, '<a class="gscw-internallink gecw-internallink" href="' + strInternalPageURL_a + '$1&title=$2">$2</a>');

	// External Links
	strResult = strResult.replace(/\[(http[s]?:\/\/(.*?))\|(.*?)\]/g, '<a class="gscw-link" href="$1" target="_blank">$3</a>');

	strResult = strResult.replace(/\{\|\s*/g, '<table class="gscw-table"><tr>');
	strResult = strResult.replace(/\|-\s*/g, '</tr><tr>');
	strResult = strResult.replace(/\!\!/g, '</th><th>');
	strResult = strResult.replace(/\! /g, '<th>');
	strResult = strResult.replace(/\|\}\s*/g, '</tr></table>');
	strResult = strResult.replace(/\|\|/g, '</td><td>');
	strResult = strResult.replace(/\| /g, '<td>');

	// Handle lists by parsing line by line
	var arrLines = strResult.split('\n');
	var arrNewLines = [];
	var strListHtml = '';
	var strListType = null;

	for (intI = 0; intI < arrLines.length; intI++) 
	{
		var strLine = arrLines[intI];

		// Check for unordered list item
		if (strLine.match(/^\*/)) 
		{
			if (strListType !== 'ul') 
			{
				// Close previous list block if needed
				if (strListType) 
				{
					arrNewLines.push(strListHtml + (strListType === 'ol' ? '</ol>' : '</ul>'));
				}

				// Start a new UL
				strListHtml = '<ul class="gscw-list">';
				strListType = 'ul';
			}

			// Append list item
			strListHtml += '<li>' + strLine.replace(/^\*/, '').trim() + '</li>';
		}
		// Check for ordered list item
		else if (strLine.match(/^#/)) 
		{
			if (strListType !== 'ol') 
			{
				// Close previous list block if there is one
				if (strListType) 
				{
					arrNewLines.push(strListHtml + (strListType === 'ol' ? '</ol>' : '</ul>'));
				}

				// Start a new OL
				strListHtml = '<ol class="gscw-list">';
				strListType = 'ol';
			}
			
			// Append list item
			strListHtml += '<li>' + strLine.replace(/^#/, '').trim() + '</li>';
		}
		// Not a list line
		else 
		{
			// If we were in a list, close it
			if (strListType) 
			{
				arrNewLines.push(strListHtml + (strListType === 'ol' ? '</ol>' : '</ul>'));
				strListType = null;
				strListHtml = '';
			}

			// Push the current line as normal content
			if (strLine.match(/<(h[1-3]|span|a|img).*?>/)) 
			{
				arrNewLines.push(strLine);
			} 
			else 
			{
				arrNewLines.push(strLine);
			}
		}
	}

	// If still in a list, close out the list
	if (strListType) 
	{
		arrNewLines.push(strListHtml + (strListType === 'ol' ? '</ol>' : '</ul>'));
	}

	// Rejoin processed lines
	strResult = arrNewLines.join('<br>');

	// Restore code blocks (this replaces the old inline code block handling)
// Restore code blocks (this replaces the old inline code block handling)
	for (intI = 0; intI < arrCodeBlocks.length; intI++) 
	{
		// HTML escape the code content
		var strEscapedCode = arrCodeBlocks[intI]
			.replace(/&/g, '&amp;')
			.replace(/</g, '&lt;')
			.replace(/>/g, '&gt;');
		
		strResult = strResult.replace('~CODE_' + intI + '~', '<pre class="gscw-code">' + strEscapedCode + '</pre>');
	}

	return strResult;
}/**
 * Intro.js v0.9.0
 * https://github.com/usablica/intro.js
 * MIT licensed
 *
 * Copyright (C) 2013 usabli.ca - A weekend project by Afshin Mehrabani (@afshinmeh)
 */

(function (root, factory) {
  if (typeof exports === 'object') {
    // CommonJS
    factory(exports);
  } else if (typeof define === 'function' && define.amd) {
    // AMD. Register as an anonymous module.
    define(['exports'], factory);
  } else {
    // Browser globals
    factory(root);
  }
} (this, function (exports) {
  //Default config/variables
  var VERSION = '0.9.0';

  /**
   * IntroJs main class
   *
   * @class IntroJs
   */
  function IntroJs(obj) {
    this._targetElement = obj;

    this._options = {
      /* Next button label in tooltip box */
      nextLabel: 'Next &rarr;',
      /* Previous button label in tooltip box */
      prevLabel: '&larr; Back',
      /* Skip button label in tooltip box */
      skipLabel: 'Skip',
      /* Done button label in tooltip box */
      doneLabel: 'Done',
      /* Default tooltip box position */
      tooltipPosition: 'bottom',
      /* Next CSS class for tooltip boxes */
      tooltipClass: '',
      /* Close introduction when pressing Escape button? */
      exitOnEsc: true,
      /* Close introduction when clicking on overlay layer? */
      exitOnOverlayClick: true,
      /* Show step numbers in introduction? */
      showStepNumbers: true,
      /* Let user use keyboard to navigate the tour? */
      keyboardNavigation: true,
      /* Show tour control buttons? */
      showButtons: true,
      /* Show tour bullets? */
      showBullets: true,
      /* Scroll to highlighted element? */
      scrollToElement: true,
      /* Set the overlay opacity */
      overlayOpacity: 0.8
    };
  }

  /**
   * Initiate a new introduction/guide from an element in the page
   *
   * @api private
   * @method _introForElement
   * @param {Object} targetElm
   * @returns {Boolean} Success or not?
   */
  function _introForElement(targetElm) {
    var introItems = [],
        self = this;

    if (this._options.steps) {
      //use steps passed programmatically
      var allIntroSteps = [];

      for (var i = 0, stepsLength = this._options.steps.length; i < stepsLength; i++) {
        var currentItem = _cloneObject(this._options.steps[i]);
        //set the step
        currentItem.step = introItems.length + 1;
        //use querySelector function only when developer used CSS selector
        if (typeof(currentItem.element) === 'string') {
          //grab the element with given selector from the page
          currentItem.element = document.querySelector(currentItem.element);
        }

        //intro without element
        if (typeof(currentItem.element) === 'undefined' || currentItem.element == null) {
          var floatingElementQuery = document.querySelector(".introjsFloatingElement");

          if (floatingElementQuery == null) {
            floatingElementQuery = document.createElement('div');
            floatingElementQuery.className = 'introjsFloatingElement';

            document.body.appendChild(floatingElementQuery);
          }

          currentItem.element  = floatingElementQuery;
          currentItem.position = 'floating';
        }

        if (currentItem.element != null) {
          introItems.push(currentItem);
        }
      }

    } else {
       //use steps from data-* annotations
      var allIntroSteps = targetElm.querySelectorAll('*[data-intro]');
      //if there's no element to intro
      if (allIntroSteps.length < 1) {
        return false;
      }

      //first add intro items with data-step
      for (var i = 0, elmsLength = allIntroSteps.length; i < elmsLength; i++) {
        var currentElement = allIntroSteps[i];
        var step = parseInt(currentElement.getAttribute('data-step'), 10);

        if (step > 0) {
          introItems[step - 1] = {
            element: currentElement,
            intro: currentElement.getAttribute('data-intro'),
            step: parseInt(currentElement.getAttribute('data-step'), 10),
            tooltipClass: currentElement.getAttribute('data-tooltipClass'),
            position: currentElement.getAttribute('data-position') || this._options.tooltipPosition
          };
        }
      }

      //next add intro items without data-step
      //todo: we need a cleanup here, two loops are redundant
      var nextStep = 0;
      for (var i = 0, elmsLength = allIntroSteps.length; i < elmsLength; i++) {
        var currentElement = allIntroSteps[i];

        if (currentElement.getAttribute('data-step') == null) {

          while (true) {
            if (typeof introItems[nextStep] == 'undefined') {
              break;
            } else {
              nextStep++;
            }
          }

          introItems[nextStep] = {
            element: currentElement,
            intro: currentElement.getAttribute('data-intro'),
            step: nextStep + 1,
            tooltipClass: currentElement.getAttribute('data-tooltipClass'),
            position: currentElement.getAttribute('data-position') || this._options.tooltipPosition
          };
        }
      }
    }

    //removing undefined/null elements
    var tempIntroItems = [];
    for (var z = 0; z < introItems.length; z++) {
      introItems[z] && tempIntroItems.push(introItems[z]);  // copy non-empty values to the end of the array
    }

    introItems = tempIntroItems;

    //Ok, sort all items with given steps
    introItems.sort(function (a, b) {
      return a.step - b.step;
    });

    //set it to the introJs object
    self._introItems = introItems;

    //add overlay layer to the page
    if(_addOverlayLayer.call(self, targetElm)) {
      //then, start the show
      _nextStep.call(self);

      var skipButton     = targetElm.querySelector('.introjs-skipbutton'),
          nextStepButton = targetElm.querySelector('.introjs-nextbutton');

      self._onKeyDown = function(e) {
        if (e.keyCode === 27 && self._options.exitOnEsc == true) {
          //escape key pressed, exit the intro
          _exitIntro.call(self, targetElm);
          //check if any callback is defined
          if (self._introExitCallback != undefined) {
            self._introExitCallback.call(self);
          }
        } else if(e.keyCode === 37) {
          //left arrow
          _previousStep.call(self);
        } else if (e.keyCode === 39 || e.keyCode === 13) {
          //right arrow or enter
          _nextStep.call(self);
          //prevent default behaviour on hitting Enter, to prevent steps being skipped in some browsers
          if(e.preventDefault) {
            e.preventDefault();
          } else {
            e.returnValue = false;
          }
        }
      };

      self._onResize = function(e) {
        _setHelperLayerPosition.call(self, document.querySelector('.introjs-helperLayer'));
      };

      if (window.addEventListener) {
        if (this._options.keyboardNavigation) {
          window.addEventListener('keydown', self._onKeyDown, true);
        }
        //for window resize
        window.addEventListener("resize", self._onResize, true);
      } else if (document.attachEvent) { //IE
        if (this._options.keyboardNavigation) {
          document.attachEvent('onkeydown', self._onKeyDown);
        }
        //for window resize
        document.attachEvent("onresize", self._onResize);
      }
    }
    return false;
  }

 /*
   * makes a copy of the object
   * @api private
   * @method _cloneObject
  */
  function _cloneObject(object) {
      if (object == null || typeof (object) != 'object' || typeof (object.nodeType) != 'undefined') {
          return object;
      }
      var temp = {};
      for (var key in object) {
          temp[key] = _cloneObject(object[key]);
      }
      return temp;
  }
  /**
   * Go to specific step of introduction
   *
   * @api private
   * @method _goToStep
   */
  function _goToStep(step) {
    //because steps starts with zero
    this._currentStep = step - 2;
    if (typeof (this._introItems) !== 'undefined') {
      _nextStep.call(this);
    }
  }

  /**
   * Go to next step on intro
   *
   * @api private
   * @method _nextStep
   */
  function _nextStep() {
    this._direction = 'forward';

    if (typeof (this._currentStep) === 'undefined') {
      this._currentStep = 0;
    } else {
      ++this._currentStep;
    }

    if ((this._introItems.length) <= this._currentStep) {
      //end of the intro
      //check if any callback is defined
      if (typeof (this._introCompleteCallback) === 'function') {
        this._introCompleteCallback.call(this);
      }
      _exitIntro.call(this, this._targetElement);
      return;
    }

    var nextStep = this._introItems[this._currentStep];
    if (typeof (this._introBeforeChangeCallback) !== 'undefined') {
      this._introBeforeChangeCallback.call(this, nextStep.element);
    }

    _showElement.call(this, nextStep);
  }

  /**
   * Go to previous step on intro
   *
   * @api private
   * @method _nextStep
   */
  function _previousStep() {
    this._direction = 'backward';

    if (this._currentStep === 0) {
      return false;
    }

    var nextStep = this._introItems[--this._currentStep];
    if (typeof (this._introBeforeChangeCallback) !== 'undefined') {
      this._introBeforeChangeCallback.call(this, nextStep.element);
    }

    _showElement.call(this, nextStep);
  }

  /**
   * Exit from intro
   *
   * @api private
   * @method _exitIntro
   * @param {Object} targetElement
   */
  function _exitIntro(targetElement) {
    //remove overlay layer from the page
    var overlayLayer = targetElement.querySelector('.introjs-overlay');

    //return if intro already completed or skipped
    if (overlayLayer == null) {
      return;
    }

    //for fade-out animation
    overlayLayer.style.opacity = 0;
    setTimeout(function () {
      if (overlayLayer.parentNode) {
        overlayLayer.parentNode.removeChild(overlayLayer);
      }
    }, 500);

    //remove all helper layers
    var helperLayer = targetElement.querySelector('.introjs-helperLayer');
    if (helperLayer) {
      helperLayer.parentNode.removeChild(helperLayer);
    }

    //remove intro floating element
    var floatingElement = document.querySelector('.introjsFloatingElement');
    if (floatingElement) {
      floatingElement.parentNode.removeChild(floatingElement);
    }

    //remove `introjs-showElement` class from the element
    var showElement = document.querySelector('.introjs-showElement');
    if (showElement) {
      showElement.className = showElement.className.replace(/introjs-[a-zA-Z]+/g, '').replace(/^\s+|\s+$/g, ''); // This is a manual trim.
    }

    //remove `introjs-fixParent` class from the elements
    var fixParents = document.querySelectorAll('.introjs-fixParent');
    if (fixParents && fixParents.length > 0) {
      for (var i = fixParents.length - 1; i >= 0; i--) {
        fixParents[i].className = fixParents[i].className.replace(/introjs-fixParent/g, '').replace(/^\s+|\s+$/g, '');
      };
    }

    //clean listeners
    if (window.removeEventListener) {
      window.removeEventListener('keydown', this._onKeyDown, true);
    } else if (document.detachEvent) { //IE
      document.detachEvent('onkeydown', this._onKeyDown);
    }

    //set the step to zero
    this._currentStep = undefined;
  }

  /**
   * Render tooltip box in the page
   *
   * @api private
   * @method _placeTooltip
   * @param {Object} targetElement
   * @param {Object} tooltipLayer
   * @param {Object} arrowLayer
   */
  function _placeTooltip(targetElement, tooltipLayer, arrowLayer, helperNumberLayer) {
    var tooltipCssClass = '',
        currentStepObj,
        tooltipOffset,
        targetElementOffset;

    //reset the old style
    tooltipLayer.style.top        = null;
    tooltipLayer.style.right      = null;
    tooltipLayer.style.bottom     = null;
    tooltipLayer.style.left       = null;
    tooltipLayer.style.marginLeft = null;
    tooltipLayer.style.marginTop  = null;

    arrowLayer.style.display = 'inherit';

    if (typeof(helperNumberLayer) != 'undefined' && helperNumberLayer != null) {
      helperNumberLayer.style.top  = null;
      helperNumberLayer.style.left = null;
    }

    //prevent error when `this._currentStep` is undefined
    if (!this._introItems[this._currentStep]) return;

    //if we have a custom css class for each step
    currentStepObj = this._introItems[this._currentStep];
    if (typeof (currentStepObj.tooltipClass) === 'string') {
      tooltipCssClass = currentStepObj.tooltipClass;
    } else {
      tooltipCssClass = this._options.tooltipClass;
    }

    tooltipLayer.className = ('introjs-tooltip ' + tooltipCssClass).replace(/^\s+|\s+$/g, '');

    //custom css class for tooltip boxes
    var tooltipCssClass = this._options.tooltipClass;

    currentTooltipPosition = this._introItems[this._currentStep].position;
    switch (currentTooltipPosition) {
      case 'top':
        tooltipLayer.style.left = '15px';
        tooltipLayer.style.top = '-' + (_getOffset(tooltipLayer).height + 10) + 'px';
        arrowLayer.className = 'introjs-arrow bottom';
        break;
      case 'right':
        tooltipLayer.style.left = (_getOffset(targetElement).width + 20) + 'px';
        arrowLayer.className = 'introjs-arrow left';
        break;
      case 'left':
        if (this._options.showStepNumbers == true) {
          tooltipLayer.style.top = '15px';
        }
        tooltipLayer.style.right = (_getOffset(targetElement).width + 20) + 'px';
        arrowLayer.className = 'introjs-arrow right';
        break;
      case 'floating':
        arrowLayer.style.display = 'none';

        //we have to adjust the top and left of layer manually for intro items without element
        tooltipOffset = _getOffset(tooltipLayer);

        tooltipLayer.style.left   = '50%';
        tooltipLayer.style.top    = '50%';
        tooltipLayer.style.marginLeft = '-' + (tooltipOffset.width / 2)  + 'px';
        tooltipLayer.style.marginTop  = '-' + (tooltipOffset.height / 2) + 'px';

        if (typeof(helperNumberLayer) != 'undefined' && helperNumberLayer != null) {
          helperNumberLayer.style.left = '-' + ((tooltipOffset.width / 2) + 18) + 'px';
          helperNumberLayer.style.top  = '-' + ((tooltipOffset.height / 2) + 18) + 'px';
        }

        break;
      case 'bottom-right-aligned':
        arrowLayer.className      = 'introjs-arrow top-right';
        tooltipLayer.style.right  = '0px';
        tooltipLayer.style.bottom = '-' + (_getOffset(tooltipLayer).height + 10) + 'px';
        break;
      case 'bottom-middle-aligned':
        targetElementOffset = _getOffset(targetElement);
        tooltipOffset       = _getOffset(tooltipLayer);

        arrowLayer.className      = 'introjs-arrow top-middle';
        tooltipLayer.style.left   = (targetElementOffset.width / 2 - tooltipOffset.width / 2) + 'px';
        tooltipLayer.style.bottom = '-' + (tooltipOffset.height + 10) + 'px';
        break;
      case 'bottom-left-aligned':
      // Bottom-left-aligned is the same as the default bottom
      case 'bottom':
      // Bottom going to follow the default behavior
      default:
        tooltipLayer.style.bottom = '-' + (_getOffset(tooltipLayer).height + 10) + 'px';
        arrowLayer.className = 'introjs-arrow top';
        break;
    }
  }

  /**
   * Update the position of the helper layer on the screen
   *
   * @api private
   * @method _setHelperLayerPosition
   * @param {Object} helperLayer
   */
  function _setHelperLayerPosition(helperLayer) {
    if (helperLayer) {
      //prevent error when `this._currentStep` in undefined
      if (!this._introItems[this._currentStep]) return;

      var currentElement  = this._introItems[this._currentStep],
          elementPosition = _getOffset(currentElement.element),
          widthHeightPadding = 10;

      if (currentElement.position == 'floating') {
        widthHeightPadding = 0;
      }

      //set new position to helper layer
      helperLayer.setAttribute('style', 'width: ' + (elementPosition.width  + widthHeightPadding)  + 'px; ' +
                                        'height:' + (elementPosition.height + widthHeightPadding)  + 'px; ' +
                                        'top:'    + (elementPosition.top    - 5)   + 'px;' +
                                        'left: '  + (elementPosition.left   - 5)   + 'px;');
    }
  }

  /**
   * Show an element on the page
   *
   * @api private
   * @method _showElement
   * @param {Object} targetElement
   */
  function _showElement(targetElement) {

    if (typeof (this._introChangeCallback) !== 'undefined') {
        this._introChangeCallback.call(this, targetElement.element);
    }

    var self = this,
        oldHelperLayer = document.querySelector('.introjs-helperLayer'),
        elementPosition = _getOffset(targetElement.element);

    if (oldHelperLayer != null) {
      var oldHelperNumberLayer = oldHelperLayer.querySelector('.introjs-helperNumberLayer'),
          oldtooltipLayer      = oldHelperLayer.querySelector('.introjs-tooltiptext'),
          oldArrowLayer        = oldHelperLayer.querySelector('.introjs-arrow'),
          oldtooltipContainer  = oldHelperLayer.querySelector('.introjs-tooltip'),
          skipTooltipButton    = oldHelperLayer.querySelector('.introjs-skipbutton'),
          prevTooltipButton    = oldHelperLayer.querySelector('.introjs-prevbutton'),
          nextTooltipButton    = oldHelperLayer.querySelector('.introjs-nextbutton');

      //hide the tooltip
      oldtooltipContainer.style.opacity = 0;

      if (oldHelperNumberLayer != null) {
        var lastIntroItem = this._introItems[(targetElement.step - 2 >= 0 ? targetElement.step - 2 : 0)];

        if (lastIntroItem != null && (this._direction == 'forward' && lastIntroItem.position == 'floating') || (this._direction == 'backward' && targetElement.position == 'floating')) {
          oldHelperNumberLayer.style.opacity = 0;
        }
      }

      //set new position to helper layer
      _setHelperLayerPosition.call(self, oldHelperLayer);

      //remove `introjs-fixParent` class from the elements
      var fixParents = document.querySelectorAll('.introjs-fixParent');
      if (fixParents && fixParents.length > 0) {
        for (var i = fixParents.length - 1; i >= 0; i--) {
          fixParents[i].className = fixParents[i].className.replace(/introjs-fixParent/g, '').replace(/^\s+|\s+$/g, '');
        };
      }

      //remove old classes
      var oldShowElement = document.querySelector('.introjs-showElement');
      oldShowElement.className = oldShowElement.className.replace(/introjs-[a-zA-Z]+/g, '').replace(/^\s+|\s+$/g, '');
      //we should wait until the CSS3 transition is competed (it's 0.3 sec) to prevent incorrect `height` and `width` calculation
      if (self._lastShowElementTimer) {
        clearTimeout(self._lastShowElementTimer);
      }
      self._lastShowElementTimer = setTimeout(function() {
        //set current step to the label
        if (oldHelperNumberLayer != null) {
          oldHelperNumberLayer.innerHTML = targetElement.step;
        }
        //set current tooltip text
        oldtooltipLayer.innerHTML = targetElement.intro;
        //set the tooltip position
        _placeTooltip.call(self, targetElement.element, oldtooltipContainer, oldArrowLayer, oldHelperNumberLayer);

        //change active bullet
        oldHelperLayer.querySelector('.introjs-bullets li > a.active').className = '';
        oldHelperLayer.querySelector('.introjs-bullets li > a[data-stepnumber="' + targetElement.step + '"]').className = 'active';

        //show the tooltip
        oldtooltipContainer.style.opacity = 1;
        if (oldHelperNumberLayer) oldHelperNumberLayer.style.opacity = 1;
      }, 350);

    } else {
      var helperLayer       = document.createElement('div'),
          arrowLayer        = document.createElement('div'),
          tooltipLayer      = document.createElement('div'),
          tooltipTextLayer  = document.createElement('div'),
          bulletsLayer      = document.createElement('div'),
          buttonsLayer      = document.createElement('div');

      helperLayer.className = 'introjs-helperLayer';

      //set new position to helper layer
      _setHelperLayerPosition.call(self, helperLayer);

      //add helper layer to target element
      this._targetElement.appendChild(helperLayer);

      arrowLayer.className = 'introjs-arrow';

      tooltipTextLayer.className = 'introjs-tooltiptext';
      tooltipTextLayer.innerHTML = targetElement.intro;

      bulletsLayer.className = 'introjs-bullets';

      if (this._options.showBullets === false) {
        bulletsLayer.style.display = 'none';
      }

      var ulContainer = document.createElement('ul');

      for (var i = 0, stepsLength = this._introItems.length; i < stepsLength; i++) {
        var innerLi    = document.createElement('li');
        var anchorLink = document.createElement('a');

        anchorLink.onclick = function() {
          self.goToStep(this.getAttribute('data-stepnumber'));
        };

        if (i === 0) anchorLink.className = "active";

        anchorLink.href = 'javascript:void(0);';
        anchorLink.innerHTML = "&nbsp;";
        anchorLink.setAttribute('data-stepnumber', this._introItems[i].step);

        innerLi.appendChild(anchorLink);
        ulContainer.appendChild(innerLi);
      }

      bulletsLayer.appendChild(ulContainer);

      buttonsLayer.className = 'introjs-tooltipbuttons';
      if (this._options.showButtons === false) {
        buttonsLayer.style.display = 'none';
      }

      tooltipLayer.className = 'introjs-tooltip';
      tooltipLayer.appendChild(tooltipTextLayer);
      tooltipLayer.appendChild(bulletsLayer);

      //add helper layer number
      if (this._options.showStepNumbers == true) {
        var helperNumberLayer = document.createElement('span');
        helperNumberLayer.className = 'introjs-helperNumberLayer';
        helperNumberLayer.innerHTML = targetElement.step;
        helperLayer.appendChild(helperNumberLayer);
      }
      tooltipLayer.appendChild(arrowLayer);
      helperLayer.appendChild(tooltipLayer);

      //next button
      var nextTooltipButton = document.createElement('a');

      nextTooltipButton.onclick = function() {
        if (self._introItems.length - 1 != self._currentStep) {
          _nextStep.call(self);
        }
      };

      nextTooltipButton.href = 'javascript:void(0);';
      nextTooltipButton.innerHTML = this._options.nextLabel;

      //previous button
      var prevTooltipButton = document.createElement('a');

      prevTooltipButton.onclick = function() {
        if (self._currentStep != 0) {
          _previousStep.call(self);
        }
      };

      prevTooltipButton.href = 'javascript:void(0);';
      prevTooltipButton.innerHTML = this._options.prevLabel;

      //skip button
      var skipTooltipButton = document.createElement('a');
      skipTooltipButton.className = 'introjs-button introjs-skipbutton';
      skipTooltipButton.href = 'javascript:void(0);';
      skipTooltipButton.innerHTML = this._options.skipLabel;

      skipTooltipButton.onclick = function() {
        if (self._introItems.length - 1 == self._currentStep && typeof (self._introCompleteCallback) === 'function') {
          self._introCompleteCallback.call(self);
        }

        if (self._introItems.length - 1 != self._currentStep && typeof (self._introExitCallback) === 'function') {
          self._introExitCallback.call(self);
        }

        _exitIntro.call(self, self._targetElement);
      };

      buttonsLayer.appendChild(skipTooltipButton);

      //in order to prevent displaying next/previous button always
      if (this._introItems.length > 1) {
        buttonsLayer.appendChild(prevTooltipButton);
        buttonsLayer.appendChild(nextTooltipButton);
      }

      tooltipLayer.appendChild(buttonsLayer);

      //set proper position
      _placeTooltip.call(self, targetElement.element, tooltipLayer, arrowLayer, helperNumberLayer);
    }

    if (this._currentStep == 0 && this._introItems.length > 1) {
      prevTooltipButton.className = 'introjs-button introjs-prevbutton introjs-disabled';
      nextTooltipButton.className = 'introjs-button introjs-nextbutton';
      skipTooltipButton.innerHTML = this._options.skipLabel;
    } else if (this._introItems.length - 1 == this._currentStep || this._introItems.length == 1) {
      skipTooltipButton.innerHTML = this._options.doneLabel;
      prevTooltipButton.className = 'introjs-button introjs-prevbutton';
      nextTooltipButton.className = 'introjs-button introjs-nextbutton introjs-disabled';
    } else {
      prevTooltipButton.className = 'introjs-button introjs-prevbutton';
      nextTooltipButton.className = 'introjs-button introjs-nextbutton';
      skipTooltipButton.innerHTML = this._options.skipLabel;
    }

    //Set focus on "next" button, so that hitting Enter always moves you onto the next step
    nextTooltipButton.focus();

    //add target element position style
    targetElement.element.className += ' introjs-showElement';

    var currentElementPosition = _getPropValue(targetElement.element, 'position');
    if (currentElementPosition !== 'absolute' &&
        currentElementPosition !== 'relative') {
      //change to new intro item
      targetElement.element.className += ' introjs-relativePosition';
    }

    var parentElm = targetElement.element.parentNode;
    while (parentElm != null) {
      if (parentElm.tagName.toLowerCase() === 'body') break;

      //fix The Stacking Contenxt problem.
      //More detail: https://developer.mozilla.org/en-US/docs/Web/Guide/CSS/Understanding_z_index/The_stacking_context
      var zIndex = _getPropValue(parentElm, 'z-index');
      var opacity = parseFloat(_getPropValue(parentElm, 'opacity'));
      if (/[0-9]+/.test(zIndex) || opacity < 1) {
        parentElm.className += ' introjs-fixParent';
      }

      parentElm = parentElm.parentNode;
    }

    if (!_elementInViewport(targetElement.element) && this._options.scrollToElement === true) {
      var rect = targetElement.element.getBoundingClientRect(),
        winHeight=_getWinSize().height,
        top = rect.bottom - (rect.bottom - rect.top),
        bottom = rect.bottom - winHeight;

      //Scroll up
      if (top < 0 || targetElement.element.clientHeight > winHeight) {
        window.scrollBy(0, top - 30); // 30px padding from edge to look nice

      //Scroll down
      } else {
        window.scrollBy(0, bottom + 100); // 70px + 30px padding from edge to look nice
      }
    }

    if (typeof (this._introAfterChangeCallback) !== 'undefined') {
        this._introAfterChangeCallback.call(this, targetElement.element);
    }
  }

  /**
   * Get an element CSS property on the page
   * Thanks to JavaScript Kit: http://www.javascriptkit.com/dhtmltutors/dhtmlcascade4.shtml
   *
   * @api private
   * @method _getPropValue
   * @param {Object} element
   * @param {String} propName
   * @returns Element's property value
   */
  function _getPropValue (element, propName) {
    var propValue = '';
    if (element.currentStyle) { //IE
      propValue = element.currentStyle[propName];
    } else if (document.defaultView && document.defaultView.getComputedStyle) { //Others
      propValue = document.defaultView.getComputedStyle(element, null).getPropertyValue(propName);
    }

    //Prevent exception in IE
    if (propValue && propValue.toLowerCase) {
      return propValue.toLowerCase();
    } else {
      return propValue;
    }
  }

  /**
   * Provides a cross-browser way to get the screen dimensions
   * via: http://stackoverflow.com/questions/5864467/internet-explorer-innerheight
   *
   * @api private
   * @method _getWinSize
   * @returns {Object} width and height attributes
   */
  function _getWinSize() {
    if (window.innerWidth != undefined) {
      return { width: window.innerWidth, height: window.innerHeight };
    } else {
      var D = document.documentElement;
      return { width: D.clientWidth, height: D.clientHeight };
    }
  }

  /**
   * Add overlay layer to the page
   * http://stackoverflow.com/questions/123999/how-to-tell-if-a-dom-element-is-visible-in-the-current-viewport
   *
   * @api private
   * @method _elementInViewport
   * @param {Object} el
   */
  function _elementInViewport(el) {
    var rect = el.getBoundingClientRect();

    return (
      rect.top >= 0 &&
      rect.left >= 0 &&
      (rect.bottom+80) <= window.innerHeight && // add 80 to get the text right
      rect.right <= window.innerWidth
    );
  }

  /**
   * Add overlay layer to the page
   *
   * @api private
   * @method _addOverlayLayer
   * @param {Object} targetElm
   */
  function _addOverlayLayer(targetElm) {
    var overlayLayer = document.createElement('div'),
        styleText = '',
        self = this;

    //set css class name
    overlayLayer.className = 'introjs-overlay';

    //check if the target element is body, we should calculate the size of overlay layer in a better way
    if (targetElm.tagName.toLowerCase() === 'body') {
      styleText += 'top: 0;bottom: 0; left: 0;right: 0;position: fixed;';
      overlayLayer.setAttribute('style', styleText);
    } else {
      //set overlay layer position
      var elementPosition = _getOffset(targetElm);
      if (elementPosition) {
        styleText += 'width: ' + elementPosition.width + 'px; height:' + elementPosition.height + 'px; top:' + elementPosition.top + 'px;left: ' + elementPosition.left + 'px;';
        overlayLayer.setAttribute('style', styleText);
      }
    }

    targetElm.appendChild(overlayLayer);

    overlayLayer.onclick = function() {
      if (self._options.exitOnOverlayClick == true) {
        _exitIntro.call(self, targetElm);

        //check if any callback is defined
        if (self._introExitCallback != undefined) {
          self._introExitCallback.call(self);
        }
      }
    };

    setTimeout(function() {
      styleText += 'opacity: ' + self._options.overlayOpacity.toString() + ';';
      overlayLayer.setAttribute('style', styleText);
    }, 10);

    return true;
  }

  /**
   * Get an element position on the page
   * Thanks to `meouw`: http://stackoverflow.com/a/442474/375966
   *
   * @api private
   * @method _getOffset
   * @param {Object} element
   * @returns Element's position info
   */
  function _getOffset(element) {
    var elementPosition = {};

    //set width
    elementPosition.width = element.offsetWidth;

    //set height
    elementPosition.height = element.offsetHeight;

    //calculate element top and left
    var _x = 0;
    var _y = 0;
    while (element && !isNaN(element.offsetLeft) && !isNaN(element.offsetTop)) {
      _x += element.offsetLeft;
      _y += element.offsetTop;
      element = element.offsetParent;
    }
    //set top
    elementPosition.top = _y;
    //set left
    elementPosition.left = _x;

    return elementPosition;
  }

  /**
   * Overwrites obj1's values with obj2's and adds obj2's if non existent in obj1
   * via: http://stackoverflow.com/questions/171251/how-can-i-merge-properties-of-two-javascript-objects-dynamically
   *
   * @param obj1
   * @param obj2
   * @returns obj3 a new object based on obj1 and obj2
   */
  function _mergeOptions(obj1,obj2) {
    var obj3 = {};
    for (var attrname in obj1) { obj3[attrname] = obj1[attrname]; }
    for (var attrname in obj2) { obj3[attrname] = obj2[attrname]; }
    return obj3;
  }

  var introJs = function (targetElm) {
    if (typeof (targetElm) === 'object') {
      //Ok, create a new instance
      return new IntroJs(targetElm);

    } else if (typeof (targetElm) === 'string') {
      //select the target element with query selector
      var targetElement = document.querySelector(targetElm);

      if (targetElement) {
        return new IntroJs(targetElement);
      } else {
        throw new Error('There is no element with given selector.');
      }
    } else {
      return new IntroJs(document.body);
    }
  };

  /**
   * Current IntroJs version
   *
   * @property version
   * @type String
   */
  introJs.version = VERSION;

  //Prototype
  introJs.fn = IntroJs.prototype = {
    clone: function () {
      return new IntroJs(this);
    },
    setOption: function(option, value) {
      this._options[option] = value;
      return this;
    },
    setOptions: function(options) {
      this._options = _mergeOptions(this._options, options);
      return this;
    },
    start: function () {
      _introForElement.call(this, this._targetElement);
      return this;
    },
    goToStep: function(step) {
      _goToStep.call(this, step);
      return this;
    },
    nextStep: function() {
      _nextStep.call(this);
      return this;
    },
    previousStep: function() {
      _previousStep.call(this);
      return this;
    },
    exit: function() {
      _exitIntro.call(this, this._targetElement);
    },
    refresh: function() {
      _setHelperLayerPosition.call(this, document.querySelector('.introjs-helperLayer'));
      return this;
    },
    onbeforechange: function(providedCallback) {
      if (typeof (providedCallback) === 'function') {
        this._introBeforeChangeCallback = providedCallback;
      } else {
        throw new Error('Provided callback for onbeforechange was not a function');
      }
      return this;
    },
    onchange: function(providedCallback) {
      if (typeof (providedCallback) === 'function') {
        this._introChangeCallback = providedCallback;
      } else {
        throw new Error('Provided callback for onchange was not a function.');
      }
      return this;
    },
    onafterchange: function(providedCallback) {
      if (typeof (providedCallback) === 'function') {
        this._introAfterChangeCallback = providedCallback;
      } else {
        throw new Error('Provided callback for onafterchange was not a function');
      }
      return this;
    },
    oncomplete: function(providedCallback) {
      if (typeof (providedCallback) === 'function') {
        this._introCompleteCallback = providedCallback;
      } else {
        throw new Error('Provided callback for oncomplete was not a function.');
      }
      return this;
    },
    onexit: function(providedCallback) {
      if (typeof (providedCallback) === 'function') {
        this._introExitCallback = providedCallback;
      } else {
        throw new Error('Provided callback for onexit was not a function.');
      }
      return this;
    }
  };

  exports.introJs = introJs;
  return introJs;
}));
/*
 * jQuery hashchange event - v1.3 - 7/21/2010
 * http://benalman.com/projects/jquery-hashchange-plugin/
 * 
 * Copyright (c) 2010 "Cowboy" Ben Alman
 * Dual licensed under the MIT and GPL licenses.
 * http://benalman.com/about/license/
 */
(function($,e,b){var c="hashchange",h=document,f,g=$.event.special,i=h.documentMode,d="on"+c in e&&(i===b||i>7);function a(j){j=j||location.href;return"#"+j.replace(/^[^#]*#?(.*)$/,"$1")}$.fn[c]=function(j){return j?this.bind(c,j):this.trigger(c)};$.fn[c].delay=50;g[c]=$.extend(g[c],{setup:function(){if(d){return false}$(f.start)},teardown:function(){if(d){return false}$(f.stop)}});f=(function(){var j={},p,m=a(),k=function(q){return q},l=k,o=k;j.start=function(){p||n()};j.stop=function(){p&&clearTimeout(p);p=b};function n(){var r=a(),q=o(m);if(r!==m){l(m=r,q);$(e).trigger(c)}else{if(q!==m){location.href=location.href.replace(/#.*/,"")+q}}p=setTimeout(n,$.fn[c].delay)}$.browser.msie&&!d&&(function(){var q,r;j.start=function(){if(!q){r=$.fn[c].src;r=r&&r+a();q=$('<iframe tabindex="-1" title="empty"/>').hide().one("load",function(){r||l(a());n()}).attr("src",r||"javascript:0").insertAfter("body")[0].contentWindow;h.onpropertychange=function(){try{if(event.propertyName==="title"){q.document.title=h.title}}catch(s){}}}};j.stop=k;o=function(){return a(q.location.href)};l=function(v,s){var u=q.document,t=$.fn[c].domain;if(v!==s){u.title=h.title;u.open();t&&u.write('<script>document.domain="'+t+'"<\/script>');u.close();q.location.hash=v}}})();return j})()})(jQuery,this);// Set caret position easily in jQuery
// Written by and Copyright of Luke Morton, 2011
// Licensed under MIT
(function ($) {
    // Behind the scenes method deals with browser
    // idiosyncrasies and such
    $.caretTo = function (el, index) {
        if (el.createTextRange) { 
            var range = el.createTextRange(); 
            range.move("character", index); 
            range.select(); 
        } else if (el.selectionStart != null) { 
            el.focus(); 
            el.setSelectionRange(index, index); 
        }
    };

    // The following methods are queued under fx for more
    // flexibility when combining with $.fn.delay() and
    // jQuery effects.

    // Set caret to a particular index
    $.fn.caretTo = function (index, offset) {
        return this.queue(function (next) {
            if (isNaN(index)) {
                var i = $(this).val().indexOf(index);
                
                if (offset === true) {
                    i += index.length;
                } else if (offset) {
                    i += offset;
                }
                
                $.caretTo(this, i);
            } else {
                $.caretTo(this, index);
            }
            
            next();
        });
    };

    // Set caret to beginning of an element
    $.fn.caretToStart = function () {
        return this.caretTo(0);
    };

    // Set caret to the end of an element
    $.fn.caretToEnd = function () {
        return this.queue(function (next) {
            $.caretTo(this, $(this).val().length);
            next();
        });
    };
}(jQuery));/*
* jQuery Form Plugin; v20130711
* http://jquery.malsup.com/form/
* Copyright (c) 2013 M. Alsup; Dual licensed: MIT/GPL
* https://github.com/malsup/form#copyright-and-license
*/
;(function(e){"use strict";function t(t){var r=t.data;t.isDefaultPrevented()||(t.preventDefault(),e(this).ajaxSubmit(r))}function r(t){var r=t.target,a=e(r);if(!a.is("[type=submit],[type=image]")){var n=a.closest("[type=submit]");if(0===n.length)return;r=n[0]}var i=this;if(i.clk=r,"image"==r.type)if(void 0!==t.offsetX)i.clk_x=t.offsetX,i.clk_y=t.offsetY;else if("function"==typeof e.fn.offset){var o=a.offset();i.clk_x=t.pageX-o.left,i.clk_y=t.pageY-o.top}else i.clk_x=t.pageX-r.offsetLeft,i.clk_y=t.pageY-r.offsetTop;setTimeout(function(){i.clk=i.clk_x=i.clk_y=null},100)}function a(){if(e.fn.ajaxSubmit.debug){var t="[jquery.form] "+Array.prototype.join.call(arguments,"");window.console&&window.console.log?window.console.log(t):window.opera&&window.opera.postError&&window.opera.postError(t)}}var n={};n.fileapi=void 0!==e("<input type='file'/>").get(0).files,n.formdata=void 0!==window.FormData;var i=!!e.fn.prop;e.fn.attr2=function(){if(!i)return this.attr.apply(this,arguments);var e=this.prop.apply(this,arguments);return e&&e.jquery||"string"==typeof e?e:this.attr.apply(this,arguments)},e.fn.ajaxSubmit=function(t){function r(r){var a,n,i=e.param(r,t.traditional).split("&"),o=i.length,s=[];for(a=0;o>a;a++)i[a]=i[a].replace(/\+/g," "),n=i[a].split("="),s.push([decodeURIComponent(n[0]),decodeURIComponent(n[1])]);return s}function o(a){for(var n=new FormData,i=0;a.length>i;i++)n.append(a[i].name,a[i].value);if(t.extraData){var o=r(t.extraData);for(i=0;o.length>i;i++)o[i]&&n.append(o[i][0],o[i][1])}t.data=null;var s=e.extend(!0,{},e.ajaxSettings,t,{contentType:!1,processData:!1,cache:!1,type:u||"POST"});t.uploadProgress&&(s.xhr=function(){var r=e.ajaxSettings.xhr();return r.upload&&r.upload.addEventListener("progress",function(e){var r=0,a=e.loaded||e.position,n=e.total;e.lengthComputable&&(r=Math.ceil(100*(a/n))),t.uploadProgress(e,a,n,r)},!1),r}),s.data=null;var l=s.beforeSend;return s.beforeSend=function(e,t){t.data=n,l&&l.call(this,e,t)},e.ajax(s)}function s(r){function n(e){var t=null;try{e.contentWindow&&(t=e.contentWindow.document)}catch(r){a("cannot get iframe.contentWindow document: "+r)}if(t)return t;try{t=e.contentDocument?e.contentDocument:e.document}catch(r){a("cannot get iframe.contentDocument: "+r),t=e.document}return t}function o(){function t(){try{var e=n(g).readyState;a("state = "+e),e&&"uninitialized"==e.toLowerCase()&&setTimeout(t,50)}catch(r){a("Server abort: ",r," (",r.name,")"),s(D),j&&clearTimeout(j),j=void 0}}var r=f.attr2("target"),i=f.attr2("action");w.setAttribute("target",d),u||w.setAttribute("method","POST"),i!=m.url&&w.setAttribute("action",m.url),m.skipEncodingOverride||u&&!/post/i.test(u)||f.attr({encoding:"multipart/form-data",enctype:"multipart/form-data"}),m.timeout&&(j=setTimeout(function(){T=!0,s(k)},m.timeout));var o=[];try{if(m.extraData)for(var l in m.extraData)m.extraData.hasOwnProperty(l)&&(e.isPlainObject(m.extraData[l])&&m.extraData[l].hasOwnProperty("name")&&m.extraData[l].hasOwnProperty("value")?o.push(e('<input type="hidden" name="'+m.extraData[l].name+'">').val(m.extraData[l].value).appendTo(w)[0]):o.push(e('<input type="hidden" name="'+l+'">').val(m.extraData[l]).appendTo(w)[0]));m.iframeTarget||(v.appendTo("body"),g.attachEvent?g.attachEvent("onload",s):g.addEventListener("load",s,!1)),setTimeout(t,15);try{w.submit()}catch(c){var p=document.createElement("form").submit;p.apply(w)}}finally{w.setAttribute("action",i),r?w.setAttribute("target",r):f.removeAttr("target"),e(o).remove()}}function s(t){if(!x.aborted&&!F){if(M=n(g),M||(a("cannot access response document"),t=D),t===k&&x)return x.abort("timeout"),S.reject(x,"timeout"),void 0;if(t==D&&x)return x.abort("server abort"),S.reject(x,"error","server abort"),void 0;if(M&&M.location.href!=m.iframeSrc||T){g.detachEvent?g.detachEvent("onload",s):g.removeEventListener("load",s,!1);var r,i="success";try{if(T)throw"timeout";var o="xml"==m.dataType||M.XMLDocument||e.isXMLDoc(M);if(a("isXml="+o),!o&&window.opera&&(null===M.body||!M.body.innerHTML)&&--O)return a("requeing onLoad callback, DOM not available"),setTimeout(s,250),void 0;var u=M.body?M.body:M.documentElement;x.responseText=u?u.innerHTML:null,x.responseXML=M.XMLDocument?M.XMLDocument:M,o&&(m.dataType="xml"),x.getResponseHeader=function(e){var t={"content-type":m.dataType};return t[e]},u&&(x.status=Number(u.getAttribute("status"))||x.status,x.statusText=u.getAttribute("statusText")||x.statusText);var l=(m.dataType||"").toLowerCase(),c=/(json|script|text)/.test(l);if(c||m.textarea){var f=M.getElementsByTagName("textarea")[0];if(f)x.responseText=f.value,x.status=Number(f.getAttribute("status"))||x.status,x.statusText=f.getAttribute("statusText")||x.statusText;else if(c){var d=M.getElementsByTagName("pre")[0],h=M.getElementsByTagName("body")[0];d?x.responseText=d.textContent?d.textContent:d.innerText:h&&(x.responseText=h.textContent?h.textContent:h.innerText)}}else"xml"==l&&!x.responseXML&&x.responseText&&(x.responseXML=X(x.responseText));try{L=_(x,l,m)}catch(b){i="parsererror",x.error=r=b||i}}catch(b){a("error caught: ",b),i="error",x.error=r=b||i}x.aborted&&(a("upload aborted"),i=null),x.status&&(i=x.status>=200&&300>x.status||304===x.status?"success":"error"),"success"===i?(m.success&&m.success.call(m.context,L,"success",x),S.resolve(x.responseText,"success",x),p&&e.event.trigger("ajaxSuccess",[x,m])):i&&(void 0===r&&(r=x.statusText),m.error&&m.error.call(m.context,x,i,r),S.reject(x,"error",r),p&&e.event.trigger("ajaxError",[x,m,r])),p&&e.event.trigger("ajaxComplete",[x,m]),p&&!--e.active&&e.event.trigger("ajaxStop"),m.complete&&m.complete.call(m.context,x,i),F=!0,m.timeout&&clearTimeout(j),setTimeout(function(){m.iframeTarget||v.remove(),x.responseXML=null},100)}}}var l,c,m,p,d,v,g,x,b,y,T,j,w=f[0],S=e.Deferred();if(r)for(c=0;h.length>c;c++)l=e(h[c]),i?l.prop("disabled",!1):l.removeAttr("disabled");if(m=e.extend(!0,{},e.ajaxSettings,t),m.context=m.context||m,d="jqFormIO"+(new Date).getTime(),m.iframeTarget?(v=e(m.iframeTarget),y=v.attr2("name"),y?d=y:v.attr2("name",d)):(v=e('<iframe name="'+d+'" src="'+m.iframeSrc+'" />'),v.css({position:"absolute",top:"-1000px",left:"-1000px"})),g=v[0],x={aborted:0,responseText:null,responseXML:null,status:0,statusText:"n/a",getAllResponseHeaders:function(){},getResponseHeader:function(){},setRequestHeader:function(){},abort:function(t){var r="timeout"===t?"timeout":"aborted";a("aborting upload... "+r),this.aborted=1;try{g.contentWindow.document.execCommand&&g.contentWindow.document.execCommand("Stop")}catch(n){}v.attr("src",m.iframeSrc),x.error=r,m.error&&m.error.call(m.context,x,r,t),p&&e.event.trigger("ajaxError",[x,m,r]),m.complete&&m.complete.call(m.context,x,r)}},p=m.global,p&&0===e.active++&&e.event.trigger("ajaxStart"),p&&e.event.trigger("ajaxSend",[x,m]),m.beforeSend&&m.beforeSend.call(m.context,x,m)===!1)return m.global&&e.active--,S.reject(),S;if(x.aborted)return S.reject(),S;b=w.clk,b&&(y=b.name,y&&!b.disabled&&(m.extraData=m.extraData||{},m.extraData[y]=b.value,"image"==b.type&&(m.extraData[y+".x"]=w.clk_x,m.extraData[y+".y"]=w.clk_y)));var k=1,D=2,A=e("meta[name=csrf-token]").attr("content"),E=e("meta[name=csrf-param]").attr("content");E&&A&&(m.extraData=m.extraData||{},m.extraData[E]=A),m.forceSync?o():setTimeout(o,10);var L,M,F,O=50,X=e.parseXML||function(e,t){return window.ActiveXObject?(t=new ActiveXObject("Microsoft.XMLDOM"),t.async="false",t.loadXML(e)):t=(new DOMParser).parseFromString(e,"text/xml"),t&&t.documentElement&&"parsererror"!=t.documentElement.nodeName?t:null},C=e.parseJSON||function(e){return window.eval("("+e+")")},_=function(t,r,a){var n=t.getResponseHeader("content-type")||"",i="xml"===r||!r&&n.indexOf("xml")>=0,o=i?t.responseXML:t.responseText;return i&&"parsererror"===o.documentElement.nodeName&&e.error&&e.error("parsererror"),a&&a.dataFilter&&(o=a.dataFilter(o,r)),"string"==typeof o&&("json"===r||!r&&n.indexOf("json")>=0?o=C(o):("script"===r||!r&&n.indexOf("javascript")>=0)&&e.globalEval(o)),o};return S}if(!this.length)return a("ajaxSubmit: skipping submit process - no element selected"),this;var u,l,c,f=this;"function"==typeof t?t={success:t}:void 0===t&&(t={}),u=t.type||this.attr2("method"),l=t.url||this.attr2("action"),c="string"==typeof l?e.trim(l):"",c=c||window.location.href||"",c&&(c=(c.match(/^([^#]+)/)||[])[1]),t=e.extend(!0,{url:c,success:e.ajaxSettings.success,type:u||"GET",iframeSrc:/^https/i.test(window.location.href||"")?"javascript:false":"about:blank"},t);var m={};if(this.trigger("form-pre-serialize",[this,t,m]),m.veto)return a("ajaxSubmit: submit vetoed via form-pre-serialize trigger"),this;if(t.beforeSerialize&&t.beforeSerialize(this,t)===!1)return a("ajaxSubmit: submit aborted via beforeSerialize callback"),this;var p=t.traditional;void 0===p&&(p=e.ajaxSettings.traditional);var d,h=[],v=this.formToArray(t.semantic,h);if(t.data&&(t.extraData=t.data,d=e.param(t.data,p)),t.beforeSubmit&&t.beforeSubmit(v,this,t)===!1)return a("ajaxSubmit: submit aborted via beforeSubmit callback"),this;if(this.trigger("form-submit-validate",[v,this,t,m]),m.veto)return a("ajaxSubmit: submit vetoed via form-submit-validate trigger"),this;var g=e.param(v,p);d&&(g=g?g+"&"+d:d),"GET"==t.type.toUpperCase()?(t.url+=(t.url.indexOf("?")>=0?"&":"?")+g,t.data=null):t.data=g;var x=[];if(t.resetForm&&x.push(function(){f.resetForm()}),t.clearForm&&x.push(function(){f.clearForm(t.includeHidden)}),!t.dataType&&t.target){var b=t.success||function(){};x.push(function(r){var a=t.replaceTarget?"replaceWith":"html";e(t.target)[a](r).each(b,arguments)})}else t.success&&x.push(t.success);if(t.success=function(e,r,a){for(var n=t.context||this,i=0,o=x.length;o>i;i++)x[i].apply(n,[e,r,a||f,f])},t.error){var y=t.error;t.error=function(e,r,a){var n=t.context||this;y.apply(n,[e,r,a,f])}}if(t.complete){var T=t.complete;t.complete=function(e,r){var a=t.context||this;T.apply(a,[e,r,f])}}var j=e('input[type=file]:enabled[value!=""]',this),w=j.length>0,S="multipart/form-data",k=f.attr("enctype")==S||f.attr("encoding")==S,D=n.fileapi&&n.formdata;a("fileAPI :"+D);var A,E=(w||k)&&!D;t.iframe!==!1&&(t.iframe||E)?t.closeKeepAlive?e.get(t.closeKeepAlive,function(){A=s(v)}):A=s(v):A=(w||k)&&D?o(v):e.ajax(t),f.removeData("jqxhr").data("jqxhr",A);for(var L=0;h.length>L;L++)h[L]=null;return this.trigger("form-submit-notify",[this,t]),this},e.fn.ajaxForm=function(n){if(n=n||{},n.delegation=n.delegation&&e.isFunction(e.fn.on),!n.delegation&&0===this.length){var i={s:this.selector,c:this.context};return!e.isReady&&i.s?(a("DOM not ready, queuing ajaxForm"),e(function(){e(i.s,i.c).ajaxForm(n)}),this):(a("terminating; zero elements found by selector"+(e.isReady?"":" (DOM not ready)")),this)}return n.delegation?(e(document).off("submit.form-plugin",this.selector,t).off("click.form-plugin",this.selector,r).on("submit.form-plugin",this.selector,n,t).on("click.form-plugin",this.selector,n,r),this):this.ajaxFormUnbind().bind("submit.form-plugin",n,t).bind("click.form-plugin",n,r)},e.fn.ajaxFormUnbind=function(){return this.unbind("submit.form-plugin click.form-plugin")},e.fn.formToArray=function(t,r){var a=[];if(0===this.length)return a;var i=this[0],o=t?i.getElementsByTagName("*"):i.elements;if(!o)return a;var s,u,l,c,f,m,p;for(s=0,m=o.length;m>s;s++)if(f=o[s],l=f.name,l&&!f.disabled)if(t&&i.clk&&"image"==f.type)i.clk==f&&(a.push({name:l,value:e(f).val(),type:f.type}),a.push({name:l+".x",value:i.clk_x},{name:l+".y",value:i.clk_y}));else if(c=e.fieldValue(f,!0),c&&c.constructor==Array)for(r&&r.push(f),u=0,p=c.length;p>u;u++)a.push({name:l,value:c[u]});else if(n.fileapi&&"file"==f.type){r&&r.push(f);var d=f.files;if(d.length)for(u=0;d.length>u;u++)a.push({name:l,value:d[u],type:f.type});else a.push({name:l,value:"",type:f.type})}else null!==c&&c!==void 0&&(r&&r.push(f),a.push({name:l,value:c,type:f.type,required:f.required}));if(!t&&i.clk){var h=e(i.clk),v=h[0];l=v.name,l&&!v.disabled&&"image"==v.type&&(a.push({name:l,value:h.val()}),a.push({name:l+".x",value:i.clk_x},{name:l+".y",value:i.clk_y}))}return a},e.fn.formSerialize=function(t){return e.param(this.formToArray(t))},e.fn.fieldSerialize=function(t){var r=[];return this.each(function(){var a=this.name;if(a){var n=e.fieldValue(this,t);if(n&&n.constructor==Array)for(var i=0,o=n.length;o>i;i++)r.push({name:a,value:n[i]});else null!==n&&n!==void 0&&r.push({name:this.name,value:n})}}),e.param(r)},e.fn.fieldValue=function(t){for(var r=[],a=0,n=this.length;n>a;a++){var i=this[a],o=e.fieldValue(i,t);null===o||void 0===o||o.constructor==Array&&!o.length||(o.constructor==Array?e.merge(r,o):r.push(o))}return r},e.fieldValue=function(t,r){var a=t.name,n=t.type,i=t.tagName.toLowerCase();if(void 0===r&&(r=!0),r&&(!a||t.disabled||"reset"==n||"button"==n||("checkbox"==n||"radio"==n)&&!t.checked||("submit"==n||"image"==n)&&t.form&&t.form.clk!=t||"select"==i&&-1==t.selectedIndex))return null;if("select"==i){var o=t.selectedIndex;if(0>o)return null;for(var s=[],u=t.options,l="select-one"==n,c=l?o+1:u.length,f=l?o:0;c>f;f++){var m=u[f];if(m.selected){var p=m.value;if(p||(p=m.attributes&&m.attributes.value&&!m.attributes.value.specified?m.text:m.value),l)return p;s.push(p)}}return s}return e(t).val()},e.fn.clearForm=function(t){return this.each(function(){e("input,select,textarea",this).clearFields(t)})},e.fn.clearFields=e.fn.clearInputs=function(t){var r=/^(?:color|date|datetime|email|month|number|password|range|search|tel|text|time|url|week)$/i;return this.each(function(){var a=this.type,n=this.tagName.toLowerCase();r.test(a)||"textarea"==n?this.value="":"checkbox"==a||"radio"==a?this.checked=!1:"select"==n?this.selectedIndex=-1:"file"==a?/MSIE/.test(navigator.userAgent)?e(this).replaceWith(e(this).clone(!0)):e(this).val(""):t&&(t===!0&&/hidden/.test(a)||"string"==typeof t&&e(this).is(t))&&(this.value="")})},e.fn.resetForm=function(){return this.each(function(){("function"==typeof this.reset||"object"==typeof this.reset&&!this.reset.nodeType)&&this.reset()})},e.fn.enable=function(e){return void 0===e&&(e=!0),this.each(function(){this.disabled=!e})},e.fn.selected=function(t){return void 0===t&&(t=!0),this.each(function(){var r=this.type;if("checkbox"==r||"radio"==r)this.checked=t;else if("option"==this.tagName.toLowerCase()){var a=e(this).parent("select");t&&a[0]&&"select-one"==a[0].type&&a.find("option").selected(!1),this.selected=t}})},e.fn.ajaxSubmit.debug=!1})(jQuery);/*
 * jQuery Input Limiter plugin 1.3.1
 * http://rustyjeans.com/jquery-plugins/input-limiter/
 *
 * Copyright (c) 2009 Russel Fones <russel@rustyjeans.com>
 *
 * Permission is hereby granted, free of charge, to any person
 * obtaining a copy of this software and associated documentation
 * files (the "Software"), to deal in the Software without
 * restriction, including without limitation the rights to use,
 * copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the
 * Software is furnished to do so, subject to the following
 * conditions:
 *
 * The above copyright notice and this permission notice shall be
 * included in all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND,
 * EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES
 * OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND
 * NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT
 * HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY,
 * WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING
 * FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR
 * OTHER DEALINGS IN THE SOFTWARE.
 */

(function ($) {
	$.fn.inputlimiter = function (options) {
		var opts = $.extend({}, $.fn.inputlimiter.defaults, options),
			$elements = $(this);
		if (opts.boxAttach && !$('#' + opts.boxId).length) {
			$('<div/>').appendTo("body").attr({id: opts.boxId, 'class': opts.boxClass}).css({'position': 'absolute'}).hide();
			// apply bgiframe if available
			if ($.fn.bgiframe) {
				$('#' + opts.boxId).bgiframe();
			}
		}

		var inputlimiterKeyup = function (e) {
			var $this = $(this),
				count = counter($this.val());
			if (!opts.allowExceed && count > opts.limit) {
				$this.val(truncater($this.val()));
			}
			if (opts.boxAttach) {
				$('#' + opts.boxId).css({
					'width': $this.outerWidth() - ($('#' + opts.boxId).outerWidth() - $('#' + opts.boxId).width()) + 'px',
					'left': $this.offset().left + 'px',
					'top': ($this.offset().top + $this.outerHeight()) - 1 + 'px',
					'z-index': 2000
				});
			}
			var charsRemaining = (opts.limit - count > 0 ? opts.limit - count : 0),
				remText = opts.remTextFilter(opts, charsRemaining),
				limitText = opts.limitTextFilter(opts);

			if (opts.limitTextShow) {
				$('#' + opts.boxId).html(remText + ' ' + limitText);
				// Check to see if the text is wrapping in the box
				// If it is lets break it between the remaining test and the limit test
				var textWidth = $("<span/>").appendTo("body").attr({id: '19cc9195583bfae1fad88e19d443be7a', 'class': opts.boxClass}).html(remText + ' ' + limitText).innerWidth();
				$("#19cc9195583bfae1fad88e19d443be7a").remove();
				if (textWidth > $('#' + opts.boxId).innerWidth()) {
					$('#' + opts.boxId).html(remText + '<br />' + limitText);
				}
				// Show the limiter box
				$('#' + opts.boxId).show();
			} else {
				$('#' + opts.boxId).html(remText).show();
			}
		},

		inputlimiterKeypress = function (e) {
			var count = counter($(this).val());
			if (!opts.allowExceed && count > opts.limit) {
				var modifierKeyPressed = e.ctrlKey || e.altKey || e.metaKey;
				if (!modifierKeyPressed && (e.which >= 32 && e.which <= 122) && this.selectionStart === this.selectionEnd) {
					return false;
				}
			}
		},

		inputlimiterBlur = function () {
			var $this = $(this);
				count = counter($this.val());
			if (!opts.allowExceed && count > opts.limit) {
				$this.val(truncater($this.val()));
			}
			if (opts.boxAttach) {
				$('#' + opts.boxId).fadeOut('fast');
			} else if (opts.remTextHideOnBlur) {
				var limitText = opts.limitText;
				limitText = limitText.replace(/\%n/g, opts.limit);
				limitText = limitText.replace(/\%s/g, (opts.limit === 1 ? '' : 's'));
				$('#' + opts.boxId).html(limitText);
			}
		},

		counter = function (value) {
			if (opts.limitBy.toLowerCase() === "words") {
				return (value.length > 0 ? $.trim(value).replace(/\ +(?= )/g, '').split(' ').length : 0);
			}
			var count = value.length,
				newlines = value.match(/\n/g);
			if (newlines && opts.lineReturnCount > 1) {
				count += newlines.length * (opts.lineReturnCount - 1);
			}
			return count;
		},

		truncater = function (value) {
			if (opts.limitBy.toLowerCase() === "words") {
				return $.trim(value).replace(/\ +(?= )/g, '').split(' ').splice(0, opts.limit).join(' ') + ' ';
			}
			return value.substring(0, opts.limit);
		};

		$(this).each(function (i) {
			var $this = $(this);
			if ((!options || !options.limit) && opts.useMaxlength && parseInt($this.attr('maxlength')) > 0 && parseInt($this.attr('maxlength')) != opts.limit) {
				$this.inputlimiter($.extend({}, opts, { limit: parseInt($this.attr('maxlength')) }));
			} else {
				if (!opts.allowExceed && opts.useMaxlength && opts.limitBy.toLowerCase() === "characters") {
					$this.attr('maxlength', opts.limit);
				}
				$this.unbind('.inputlimiter');
				$this.bind('keyup.inputlimiter', inputlimiterKeyup);
				$this.bind('keypress.inputlimiter', inputlimiterKeypress);
				$this.bind('blur.inputlimiter', inputlimiterBlur);
			}
		});
	};

	$.fn.inputlimiter.remtextfilter = function (opts, charsRemaining) {
		var remText = opts.remText;
		if (charsRemaining === 0 && opts.remFullText !== null) {
			remText = opts.remFullText;
		}
		remText = remText.replace(/\%n/g, charsRemaining);
		remText = remText.replace(/\%s/g, (opts.zeroPlural ? (charsRemaining === 1 ? '' : 's') : (charsRemaining <= 1 ? '' : 's')));
		return remText;
	};

	$.fn.inputlimiter.limittextfilter = function (opts) {
		var limitText = opts.limitText;
		limitText = limitText.replace(/\%n/g, opts.limit);
		limitText = limitText.replace(/\%s/g, (opts.limit <= 1 ? '' : 's'));
		return limitText;
	};

	$.fn.inputlimiter.defaults = {
		limit: 255,
		boxAttach: true,
		boxId: 'limiterBox',
		boxClass: 'limiterBox',
		remText: '%n character%s remaining.',
		remTextFilter: $.fn.inputlimiter.remtextfilter,
		remTextHideOnBlur: true,
		remFullText: null,
		limitTextShow: true,
		limitText: 'Field limited to %n character%s.',
		limitTextFilter: $.fn.inputlimiter.limittextfilter,
		zeroPlural: true,
		allowExceed: false,
		useMaxlength: true,
		limitBy: 'characters',
		lineReturnCount: 1
	};

})(jQuery);
/*
 * qTip2 - Pretty powerful tooltips - v2.2.0
 * http://qtip2.com
 *
 * Copyright (c) 2014 Craig Michael Thompson
 * Released under the MIT, GPL licenses
 * http://jquery.org/license
 *
 * Date: Wed Mar 19 2014 05:29 EDT-0400
 * Plugins: tips svg
 * Styles: basic css3
 */
/*global window: false, jQuery: false, console: false, define: false */

/* Cache window, document, undefined */
(function( window, document, undefined ) {

// Uses AMD or browser globals to create a jQuery plugin.
(function( factory ) {
	"use strict";
	if(typeof define === 'function' && define.amd) {
		define(['jquery'], factory);
	}
	else if(jQuery && !jQuery.fn.qtip) {
		factory(jQuery);
	}
}
(function($) {
	"use strict"; // Enable ECMAScript "strict" operation for this function. See more: http://ejohn.org/blog/ecmascript-5-strict-mode-json-and-more/

;// Munge the primitives - Paul Irish tip
var TRUE = true,
FALSE = false,
NULL = null,

// Common variables
X = 'x', Y = 'y',
WIDTH = 'width',
HEIGHT = 'height',

// Positioning sides
TOP = 'top',
LEFT = 'left',
BOTTOM = 'bottom',
RIGHT = 'right',
CENTER = 'center',

// Position adjustment types
FLIP = 'flip',
FLIPINVERT = 'flipinvert',
SHIFT = 'shift',

// Shortcut vars
QTIP, PROTOTYPE, CORNER, CHECKS,
PLUGINS = {},
NAMESPACE = 'qtip',
ATTR_HAS = 'data-hasqtip',
ATTR_ID = 'data-qtip-id',
WIDGET = ['ui-widget', 'ui-tooltip'],
SELECTOR = '.'+NAMESPACE,
INACTIVE_EVENTS = 'click dblclick mousedown mouseup mousemove mouseleave mouseenter'.split(' '),

CLASS_FIXED = NAMESPACE+'-fixed',
CLASS_DEFAULT = NAMESPACE + '-default',
CLASS_FOCUS = NAMESPACE + '-focus',
CLASS_HOVER = NAMESPACE + '-hover',
CLASS_DISABLED = NAMESPACE+'-disabled',

replaceSuffix = '_replacedByqTip',
oldtitle = 'oldtitle',
trackingBound,

// Browser detection
BROWSER = {
	/*
	 * IE version detection
	 *
	 * Adapted from: http://ajaxian.com/archives/attack-of-the-ie-conditional-comment
	 * Credit to James Padolsey for the original implemntation!
	 */
	ie: (function(){
		var v = 3, div = document.createElement('div');
		while ((div.innerHTML = '<!--[if gt IE '+(++v)+']><i></i><![endif]-->')) {
			if(!div.getElementsByTagName('i')[0]) { break; }
		}
		return v > 4 ? v : NaN;
	}()),
 
	/*
	 * iOS version detection
	 */
	iOS: parseFloat( 
		('' + (/CPU.*OS ([0-9_]{1,5})|(CPU like).*AppleWebKit.*Mobile/i.exec(navigator.userAgent) || [0,''])[1])
		.replace('undefined', '3_2').replace('_', '.').replace('_', '')
	) || FALSE
};

;function QTip(target, options, id, attr) {
	// Elements and ID
	this.id = id;
	this.target = target;
	this.tooltip = NULL;
	this.elements = { target: target };

	// Internal constructs
	this._id = NAMESPACE + '-' + id;
	this.timers = { img: {} };
	this.options = options;
	this.plugins = {};

	// Cache object
	this.cache = {
		event: {},
		target: $(),
		disabled: FALSE,
		attr: attr,
		onTooltip: FALSE,
		lastClass: ''
	};

	// Set the initial flags
	this.rendered = this.destroyed = this.disabled = this.waiting = 
		this.hiddenDuringWait = this.positioning = this.triggering = FALSE;
}
PROTOTYPE = QTip.prototype;

PROTOTYPE._when = function(deferreds) {
	return $.when.apply($, deferreds);
};

PROTOTYPE.render = function(show) {
	if(this.rendered || this.destroyed) { return this; } // If tooltip has already been rendered, exit

	var self = this,
		options = this.options,
		cache = this.cache,
		elements = this.elements,
		text = options.content.text,
		title = options.content.title,
		button = options.content.button,
		posOptions = options.position,
		namespace = '.'+this._id+' ',
		deferreds = [],
		tooltip;

	// Add ARIA attributes to target
	$.attr(this.target[0], 'aria-describedby', this._id);

	// Create tooltip element
	this.tooltip = elements.tooltip = tooltip = $('<div/>', {
		'id': this._id,
		'class': [ NAMESPACE, CLASS_DEFAULT, options.style.classes, NAMESPACE + '-pos-' + options.position.my.abbrev() ].join(' '),
		'width': options.style.width || '',
		'height': options.style.height || '',
		'tracking': posOptions.target === 'mouse' && posOptions.adjust.mouse,

		/* ARIA specific attributes */
		'role': 'alert',
		'aria-live': 'polite',
		'aria-atomic': FALSE,
		'aria-describedby': this._id + '-content',
		'aria-hidden': TRUE
	})
	.toggleClass(CLASS_DISABLED, this.disabled)
	.attr(ATTR_ID, this.id)
	.data(NAMESPACE, this)
	.appendTo(posOptions.container)
	.append(
		// Create content element
		elements.content = $('<div />', {
			'class': NAMESPACE + '-content',
			'id': this._id + '-content',
			'aria-atomic': TRUE
		})
	);

	// Set rendered flag and prevent redundant reposition calls for now
	this.rendered = -1;
	this.positioning = TRUE;

	// Create title...
	if(title) {
		this._createTitle();

		// Update title only if its not a callback (called in toggle if so)
		if(!$.isFunction(title)) {
			deferreds.push( this._updateTitle(title, FALSE) );
		}
	}

	// Create button
	if(button) { this._createButton(); }

	// Set proper rendered flag and update content if not a callback function (called in toggle)
	if(!$.isFunction(text)) {
		deferreds.push( this._updateContent(text, FALSE) );
	}
	this.rendered = TRUE;

	// Setup widget classes
	this._setWidget();

	// Initialize 'render' plugins
	$.each(PLUGINS, function(name) {
		var instance;
		if(this.initialize === 'render' && (instance = this(self))) {
			self.plugins[name] = instance;
		}
	});

	// Unassign initial events and assign proper events
	this._unassignEvents();
	this._assignEvents();

	// When deferreds have completed
	this._when(deferreds).then(function() {
		// tooltiprender event
		self._trigger('render');

		// Reset flags
		self.positioning = FALSE;

		// Show tooltip if not hidden during wait period
		if(!self.hiddenDuringWait && (options.show.ready || show)) {
			self.toggle(TRUE, cache.event, FALSE);
		}
		self.hiddenDuringWait = FALSE;
	});

	// Expose API
	QTIP.api[this.id] = this;

	return this;
};

PROTOTYPE.destroy = function(immediate) {
	// Set flag the signify destroy is taking place to plugins
	// and ensure it only gets destroyed once!
	if(this.destroyed) { return this.target; }

	function process() {
		if(this.destroyed) { return; }
		this.destroyed = TRUE;
		
		var target = this.target,
			title = target.attr(oldtitle);

		// Destroy tooltip if rendered
		if(this.rendered) {
			this.tooltip.stop(1,0).find('*').remove().end().remove();
		}

		// Destroy all plugins
		$.each(this.plugins, function(name) {
			this.destroy && this.destroy();
		});

		// Clear timers and remove bound events
		clearTimeout(this.timers.show);
		clearTimeout(this.timers.hide);
		this._unassignEvents();

		// Remove api object and ARIA attributes
		target.removeData(NAMESPACE)
			.removeAttr(ATTR_ID)
			.removeAttr(ATTR_HAS)
			.removeAttr('aria-describedby');

		// Reset old title attribute if removed
		if(this.options.suppress && title) {
			target.attr('title', title).removeAttr(oldtitle);
		}

		// Remove qTip events associated with this API
		this._unbind(target);

		// Remove ID from used id objects, and delete object references
		// for better garbage collection and leak protection
		this.options = this.elements = this.cache = this.timers = 
			this.plugins = this.mouse = NULL;

		// Delete epoxsed API object
		delete QTIP.api[this.id];
	}

	// If an immediate destory is needed
	if((immediate !== TRUE || this.triggering === 'hide') && this.rendered) {
		this.tooltip.one('tooltiphidden', $.proxy(process, this));
		!this.triggering && this.hide();
	}

	// If we're not in the process of hiding... process
	else { process.call(this); }

	return this.target;
};

;function invalidOpt(a) {
	return a === NULL || $.type(a) !== 'object';
}

function invalidContent(c) {
	return !( $.isFunction(c) || (c && c.attr) || c.length || ($.type(c) === 'object' && (c.jquery || c.then) ));
}

// Option object sanitizer
function sanitizeOptions(opts) {
	var content, text, ajax, once;

	if(invalidOpt(opts)) { return FALSE; }

	if(invalidOpt(opts.metadata)) {
		opts.metadata = { type: opts.metadata };
	}

	if('content' in opts) {
		content = opts.content;

		if(invalidOpt(content) || content.jquery || content.done) {
			content = opts.content = {
				text: (text = invalidContent(content) ? FALSE : content)
			};
		}
		else { text = content.text; }

		// DEPRECATED - Old content.ajax plugin functionality
		// Converts it into the proper Deferred syntax
		if('ajax' in content) {
			ajax = content.ajax;
			once = ajax && ajax.once !== FALSE;
			delete content.ajax;

			content.text = function(event, api) {
				var loading = text || $(this).attr(api.options.content.attr) || 'Loading...',

				deferred = $.ajax(
					$.extend({}, ajax, { context: api })
				)
				.then(ajax.success, NULL, ajax.error)
				.then(function(content) {
					if(content && once) { api.set('content.text', content); }
					return content;
				},
				function(xhr, status, error) {
					if(api.destroyed || xhr.status === 0) { return; }
					api.set('content.text', status + ': ' + error);
				});

				return !once ? (api.set('content.text', loading), deferred) : loading;
			};
		}

		if('title' in content) {
			if(!invalidOpt(content.title)) {
				content.button = content.title.button;
				content.title = content.title.text;
			}

			if(invalidContent(content.title || FALSE)) {
				content.title = FALSE;
			}
		}
	}

	if('position' in opts && invalidOpt(opts.position)) {
		opts.position = { my: opts.position, at: opts.position };
	}

	if('show' in opts && invalidOpt(opts.show)) {
		opts.show = opts.show.jquery ? { target: opts.show } : 
			opts.show === TRUE ? { ready: TRUE } : { event: opts.show };
	}

	if('hide' in opts && invalidOpt(opts.hide)) {
		opts.hide = opts.hide.jquery ? { target: opts.hide } : { event: opts.hide };
	}

	if('style' in opts && invalidOpt(opts.style)) {
		opts.style = { classes: opts.style };
	}

	// Sanitize plugin options
	$.each(PLUGINS, function() {
		this.sanitize && this.sanitize(opts);
	});

	return opts;
}

// Setup builtin .set() option checks
CHECKS = PROTOTYPE.checks = {
	builtin: {
		// Core checks
		'^id$': function(obj, o, v, prev) {
			var id = v === TRUE ? QTIP.nextid : v,
				new_id = NAMESPACE + '-' + id;

			if(id !== FALSE && id.length > 0 && !$('#'+new_id).length) {
				this._id = new_id;

				if(this.rendered) {
					this.tooltip[0].id = this._id;
					this.elements.content[0].id = this._id + '-content';
					this.elements.title[0].id = this._id + '-title';
				}
			}
			else { obj[o] = prev; }
		},
		'^prerender': function(obj, o, v) {
			v && !this.rendered && this.render(this.options.show.ready);
		},

		// Content checks
		'^content.text$': function(obj, o, v) {
			this._updateContent(v);
		},
		'^content.attr$': function(obj, o, v, prev) {
			if(this.options.content.text === this.target.attr(prev)) {
				this._updateContent( this.target.attr(v) );
			}
		},
		'^content.title$': function(obj, o, v) {
			// Remove title if content is null
			if(!v) { return this._removeTitle(); }

			// If title isn't already created, create it now and update
			v && !this.elements.title && this._createTitle();
			this._updateTitle(v);
		},
		'^content.button$': function(obj, o, v) {
			this._updateButton(v);
		},
		'^content.title.(text|button)$': function(obj, o, v) {
			this.set('content.'+o, v); // Backwards title.text/button compat
		}, 

		// Position checks
		'^position.(my|at)$': function(obj, o, v){
			'string' === typeof v && (obj[o] = new CORNER(v, o === 'at'));
		},
		'^position.container$': function(obj, o, v){
			this.rendered && this.tooltip.appendTo(v);
		},

		// Show checks
		'^show.ready$': function(obj, o, v) {
			v && (!this.rendered && this.render(TRUE) || this.toggle(TRUE));
		},

		// Style checks
		'^style.classes$': function(obj, o, v, p) {
			this.rendered && this.tooltip.removeClass(p).addClass(v);
		},
		'^style.(width|height)': function(obj, o, v) {
			this.rendered && this.tooltip.css(o, v);
		},
		'^style.widget|content.title': function() {
			this.rendered && this._setWidget();
		},
		'^style.def': function(obj, o, v) {
			this.rendered && this.tooltip.toggleClass(CLASS_DEFAULT, !!v);
		},

		// Events check
		'^events.(render|show|move|hide|focus|blur)$': function(obj, o, v) {
			this.rendered && this.tooltip[($.isFunction(v) ? '' : 'un') + 'bind']('tooltip'+o, v);
		},

		// Properties which require event reassignment
		'^(show|hide|position).(event|target|fixed|inactive|leave|distance|viewport|adjust)': function() {
			if(!this.rendered) { return; }

			// Set tracking flag
			var posOptions = this.options.position;
			this.tooltip.attr('tracking', posOptions.target === 'mouse' && posOptions.adjust.mouse);

			// Reassign events
			this._unassignEvents();
			this._assignEvents();
		}
	}
};

// Dot notation converter
function convertNotation(options, notation) {
	var i = 0, obj, option = options,

	// Split notation into array
	levels = notation.split('.');

	// Loop through
	while( option = option[ levels[i++] ] ) {
		if(i < levels.length) { obj = option; }
	}

	return [obj || options, levels.pop()];
}

PROTOTYPE.get = function(notation) {
	if(this.destroyed) { return this; }

	var o = convertNotation(this.options, notation.toLowerCase()),
		result = o[0][ o[1] ];

	return result.precedance ? result.string() : result;
};

function setCallback(notation, args) {
	var category, rule, match;

	for(category in this.checks) {
		for(rule in this.checks[category]) {
			if(match = (new RegExp(rule, 'i')).exec(notation)) {
				args.push(match);

				if(category === 'builtin' || this.plugins[category]) {
					this.checks[category][rule].apply(
						this.plugins[category] || this, args
					);
				}
			}
		}
	}
}

var rmove = /^position\.(my|at|adjust|target|container|viewport)|style|content|show\.ready/i,
	rrender = /^prerender|show\.ready/i;

PROTOTYPE.set = function(option, value) {
	if(this.destroyed) { return this; }

	var rendered = this.rendered,
		reposition = FALSE,
		options = this.options,
		checks = this.checks,
		name;

	// Convert singular option/value pair into object form
	if('string' === typeof option) {
		name = option; option = {}; option[name] = value;
	}
	else { option = $.extend({}, option); }

	// Set all of the defined options to their new values
	$.each(option, function(notation, value) {
		if(rendered && rrender.test(notation)) {
			delete option[notation]; return;
		}

		// Set new obj value
		var obj = convertNotation(options, notation.toLowerCase()), previous;
		previous = obj[0][ obj[1] ];
		obj[0][ obj[1] ] = value && value.nodeType ? $(value) : value;

		// Also check if we need to reposition
		reposition = rmove.test(notation) || reposition;

		// Set the new params for the callback
		option[notation] = [obj[0], obj[1], value, previous];
	});

	// Re-sanitize options
	sanitizeOptions(options);

	/*
	 * Execute any valid callbacks for the set options
	 * Also set positioning flag so we don't get loads of redundant repositioning calls.
	 */
	this.positioning = TRUE;
	$.each(option, $.proxy(setCallback, this));
	this.positioning = FALSE;

	// Update position if needed
	if(this.rendered && this.tooltip[0].offsetWidth > 0 && reposition) {
		this.reposition( options.position.target === 'mouse' ? NULL : this.cache.event );
	}

	return this;
};

;PROTOTYPE._update = function(content, element, reposition) {
	var self = this,
		cache = this.cache;

	// Make sure tooltip is rendered and content is defined. If not return
	if(!this.rendered || !content) { return FALSE; }

	// Use function to parse content
	if($.isFunction(content)) {
		content = content.call(this.elements.target, cache.event, this) || '';
	}

	// Handle deferred content
	if($.isFunction(content.then)) {
		cache.waiting = TRUE;
		return content.then(function(c) {
			cache.waiting = FALSE;
			return self._update(c, element);
		}, NULL, function(e) {
			return self._update(e, element);
		});
	}

	// If content is null... return false
	if(content === FALSE || (!content && content !== '')) { return FALSE; }

	// Append new content if its a DOM array and show it if hidden
	if(content.jquery && content.length > 0) {
		element.empty().append(
			content.css({ display: 'block', visibility: 'visible' })
		);
	}

	// Content is a regular string, insert the new content
	else { element.html(content); }

	// Wait for content to be loaded, and reposition
	return this._waitForContent(element).then(function(images) {
		if(images.images && images.images.length && self.rendered && self.tooltip[0].offsetWidth > 0) {
			self.reposition(cache.event, !images.length);
		}
	});
};

PROTOTYPE._waitForContent = function(element) {
	var cache = this.cache;
	
	// Set flag
	cache.waiting = TRUE;

	// If imagesLoaded is included, ensure images have loaded and return promise
	return ( $.fn.imagesLoaded ? element.imagesLoaded() : $.Deferred().resolve([]) )
		.done(function() { cache.waiting = FALSE; })
		.promise();
};

PROTOTYPE._updateContent = function(content, reposition) {
	this._update(content, this.elements.content, reposition);
};

PROTOTYPE._updateTitle = function(content, reposition) {
	if(this._update(content, this.elements.title, reposition) === FALSE) {
		this._removeTitle(FALSE);
	}
};

PROTOTYPE._createTitle = function()
{
	var elements = this.elements,
		id = this._id+'-title';

	// Destroy previous title element, if present
	if(elements.titlebar) { this._removeTitle(); }

	// Create title bar and title elements
	elements.titlebar = $('<div />', {
		'class': NAMESPACE + '-titlebar ' + (this.options.style.widget ? createWidgetClass('header') : '')
	})
	.append(
		elements.title = $('<div />', {
			'id': id,
			'class': NAMESPACE + '-title',
			'aria-atomic': TRUE
		})
	)
	.insertBefore(elements.content)

	// Button-specific events
	.delegate('.qtip-close', 'mousedown keydown mouseup keyup mouseout', function(event) {
		$(this).toggleClass('ui-state-active ui-state-focus', event.type.substr(-4) === 'down');
	})
	.delegate('.qtip-close', 'mouseover mouseout', function(event){
		$(this).toggleClass('ui-state-hover', event.type === 'mouseover');
	});

	// Create button if enabled
	if(this.options.content.button) { this._createButton(); }
};

PROTOTYPE._removeTitle = function(reposition)
{
	var elements = this.elements;

	if(elements.title) {
		elements.titlebar.remove();
		elements.titlebar = elements.title = elements.button = NULL;

		// Reposition if enabled
		if(reposition !== FALSE) { this.reposition(); }
	}
};

;PROTOTYPE.reposition = function(event, effect) {
	if(!this.rendered || this.positioning || this.destroyed) { return this; }

	// Set positioning flag
	this.positioning = TRUE;

	var cache = this.cache,
		tooltip = this.tooltip,
		posOptions = this.options.position,
		target = posOptions.target,
		my = posOptions.my,
		at = posOptions.at,
		viewport = posOptions.viewport,
		container = posOptions.container,
		adjust = posOptions.adjust,
		method = adjust.method.split(' '),
		tooltipWidth = tooltip.outerWidth(FALSE),
		tooltipHeight = tooltip.outerHeight(FALSE),
		targetWidth = 0,
		targetHeight = 0,
		type = tooltip.css('position'),
		position = { left: 0, top: 0 },
		visible = tooltip[0].offsetWidth > 0,
		isScroll = event && event.type === 'scroll',
		win = $(window),
		doc = container[0].ownerDocument,
		mouse = this.mouse,
		pluginCalculations, offset;

	// Check if absolute position was passed
	if($.isArray(target) && target.length === 2) {
		// Force left top and set position
		at = { x: LEFT, y: TOP };
		position = { left: target[0], top: target[1] };
	}

	// Check if mouse was the target
	else if(target === 'mouse') {
		// Force left top to allow flipping
		at = { x: LEFT, y: TOP };

		// Use the cached mouse coordinates if available, or passed event has no coordinates
		if(mouse && mouse.pageX && (adjust.mouse || !event || !event.pageX) ) {
			event = mouse;
		}
		
		// If the passed event has no coordinates (such as a scroll event)
		else if(!event || !event.pageX) {
			// Use the mouse origin that caused the show event, if distance hiding is enabled
			if((!adjust.mouse || this.options.show.distance) && cache.origin && cache.origin.pageX) {
				event =  cache.origin;
			}

			// Use cached event for resize/scroll events
			else if(!event || (event && (event.type === 'resize' || event.type === 'scroll'))) {
				event = cache.event;
			}
		}

		// Calculate body and container offset and take them into account below
		if(type !== 'static') { position = container.offset(); }
		if(doc.body.offsetWidth !== (window.innerWidth || doc.documentElement.clientWidth)) {
			offset = $(document.body).offset();
		}

		// Use event coordinates for position
		position = {
			left: event.pageX - position.left + (offset && offset.left || 0),
			top: event.pageY - position.top + (offset && offset.top || 0)
		};

		// Scroll events are a pain, some browsers
		if(adjust.mouse && isScroll && mouse) {
			position.left -= (mouse.scrollX || 0) - win.scrollLeft();
			position.top -= (mouse.scrollY || 0) - win.scrollTop();
		}
	}

	// Target wasn't mouse or absolute...
	else {
		// Check if event targetting is being used
		if(target === 'event') {
			if(event && event.target && event.type !== 'scroll' && event.type !== 'resize') {
				cache.target = $(event.target);
			}
			else if(!event.target) {
				cache.target = this.elements.target;
			}
		}
		else if(target !== 'event'){
			cache.target = $(target.jquery ? target : this.elements.target);
		}
		target = cache.target;

		// Parse the target into a jQuery object and make sure there's an element present
		target = $(target).eq(0);
		if(target.length === 0) { return this; }

		// Check if window or document is the target
		else if(target[0] === document || target[0] === window) {
			targetWidth = BROWSER.iOS ? window.innerWidth : target.width();
			targetHeight = BROWSER.iOS ? window.innerHeight : target.height();

			if(target[0] === window) {
				position = {
					top: (viewport || target).scrollTop(),
					left: (viewport || target).scrollLeft()
				};
			}
		}

		// Check if the target is an <AREA> element
		else if(PLUGINS.imagemap && target.is('area')) {
			pluginCalculations = PLUGINS.imagemap(this, target, at, PLUGINS.viewport ? method : FALSE);
		}

		// Check if the target is an SVG element
		else if(PLUGINS.svg && target && target[0].ownerSVGElement) {
			pluginCalculations = PLUGINS.svg(this, target, at, PLUGINS.viewport ? method : FALSE);
		}

		// Otherwise use regular jQuery methods
		else {
			targetWidth = target.outerWidth(FALSE);
			targetHeight = target.outerHeight(FALSE);
			position = target.offset();
		}

		// Parse returned plugin values into proper variables
		if(pluginCalculations) {
			targetWidth = pluginCalculations.width;
			targetHeight = pluginCalculations.height;
			offset = pluginCalculations.offset;
			position = pluginCalculations.position;
		}

		// Adjust position to take into account offset parents
		position = this.reposition.offset(target, position, container);

		// Adjust for position.fixed tooltips (and also iOS scroll bug in v3.2-4.0 & v4.3-4.3.2)
		if((BROWSER.iOS > 3.1 && BROWSER.iOS < 4.1) || 
			(BROWSER.iOS >= 4.3 && BROWSER.iOS < 4.33) || 
			(!BROWSER.iOS && type === 'fixed')
		){
			position.left -= win.scrollLeft();
			position.top -= win.scrollTop();
		}

		// Adjust position relative to target
		if(!pluginCalculations || (pluginCalculations && pluginCalculations.adjustable !== FALSE)) {
			position.left += at.x === RIGHT ? targetWidth : at.x === CENTER ? targetWidth / 2 : 0;
			position.top += at.y === BOTTOM ? targetHeight : at.y === CENTER ? targetHeight / 2 : 0;
		}
	}

	// Adjust position relative to tooltip
	position.left += adjust.x + (my.x === RIGHT ? -tooltipWidth : my.x === CENTER ? -tooltipWidth / 2 : 0);
	position.top += adjust.y + (my.y === BOTTOM ? -tooltipHeight : my.y === CENTER ? -tooltipHeight / 2 : 0);

	// Use viewport adjustment plugin if enabled
	if(PLUGINS.viewport) {
		position.adjusted = PLUGINS.viewport(
			this, position, posOptions, targetWidth, targetHeight, tooltipWidth, tooltipHeight
		);

		// Apply offsets supplied by positioning plugin (if used)
		if(offset && position.adjusted.left) { position.left += offset.left; }
		if(offset && position.adjusted.top) {  position.top += offset.top; }
	}

	// Viewport adjustment is disabled, set values to zero
	else { position.adjusted = { left: 0, top: 0 }; }

	// tooltipmove event
	if(!this._trigger('move', [position, viewport.elem || viewport], event)) { return this; }
	delete position.adjusted;

	// If effect is disabled, target it mouse, no animation is defined or positioning gives NaN out, set CSS directly
	if(effect === FALSE || !visible || isNaN(position.left) || isNaN(position.top) || target === 'mouse' || !$.isFunction(posOptions.effect)) {
		tooltip.css(position);
	}

	// Use custom function if provided
	else if($.isFunction(posOptions.effect)) {
		posOptions.effect.call(tooltip, this, $.extend({}, position));
		tooltip.queue(function(next) {
			// Reset attributes to avoid cross-browser rendering bugs
			$(this).css({ opacity: '', height: '' });
			if(BROWSER.ie) { this.style.removeAttribute('filter'); }

			next();
		});
	}

	// Set positioning flag
	this.positioning = FALSE;

	return this;
};

// Custom (more correct for qTip!) offset calculator
PROTOTYPE.reposition.offset = function(elem, pos, container) {
	if(!container[0]) { return pos; }

	var ownerDocument = $(elem[0].ownerDocument),
		quirks = !!BROWSER.ie && document.compatMode !== 'CSS1Compat',
		parent = container[0],
		scrolled, position, parentOffset, overflow;

	function scroll(e, i) {
		pos.left += i * e.scrollLeft();
		pos.top += i * e.scrollTop();
	}

	// Compensate for non-static containers offset
	do {
		if((position = $.css(parent, 'position')) !== 'static') {
			if(position === 'fixed') {
				parentOffset = parent.getBoundingClientRect();
				scroll(ownerDocument, -1);
			}
			else {
				parentOffset = $(parent).position();
				parentOffset.left += (parseFloat($.css(parent, 'borderLeftWidth')) || 0);
				parentOffset.top += (parseFloat($.css(parent, 'borderTopWidth')) || 0);
			}

			pos.left -= parentOffset.left + (parseFloat($.css(parent, 'marginLeft')) || 0);
			pos.top -= parentOffset.top + (parseFloat($.css(parent, 'marginTop')) || 0);

			// If this is the first parent element with an overflow of "scroll" or "auto", store it
			if(!scrolled && (overflow = $.css(parent, 'overflow')) !== 'hidden' && overflow !== 'visible') { scrolled = $(parent); }
		}
	}
	while((parent = parent.offsetParent));

	// Compensate for containers scroll if it also has an offsetParent (or in IE quirks mode)
	if(scrolled && (scrolled[0] !== ownerDocument[0] || quirks)) {
		scroll(scrolled, 1);
	}

	return pos;
};

// Corner class
var C = (CORNER = PROTOTYPE.reposition.Corner = function(corner, forceY) {
	corner = ('' + corner).replace(/([A-Z])/, ' $1').replace(/middle/gi, CENTER).toLowerCase();
	this.x = (corner.match(/left|right/i) || corner.match(/center/) || ['inherit'])[0].toLowerCase();
	this.y = (corner.match(/top|bottom|center/i) || ['inherit'])[0].toLowerCase();
	this.forceY = !!forceY;

	var f = corner.charAt(0);
	this.precedance = (f === 't' || f === 'b' ? Y : X);
}).prototype;

C.invert = function(z, center) {
	this[z] = this[z] === LEFT ? RIGHT : this[z] === RIGHT ? LEFT : center || this[z];	
};

C.string = function() {
	var x = this.x, y = this.y;
	return x === y ? x : this.precedance === Y || (this.forceY && y !== 'center') ? y+' '+x : x+' '+y;
};

C.abbrev = function() {
	var result = this.string().split(' ');
	return result[0].charAt(0) + (result[1] && result[1].charAt(0) || '');
};

C.clone = function() {
	return new CORNER( this.string(), this.forceY );
};;
PROTOTYPE.toggle = function(state, event) {
	var cache = this.cache,
		options = this.options,
		tooltip = this.tooltip;

	// Try to prevent flickering when tooltip overlaps show element
	if(event) {
		if((/over|enter/).test(event.type) && (/out|leave/).test(cache.event.type) &&
			options.show.target.add(event.target).length === options.show.target.length &&
			tooltip.has(event.relatedTarget).length) {
			return this;
		}

		// Cache event
		cache.event = cloneEvent(event);
	}
		
	// If we're currently waiting and we've just hidden... stop it
	this.waiting && !state && (this.hiddenDuringWait = TRUE);

	// Render the tooltip if showing and it isn't already
	if(!this.rendered) { return state ? this.render(1) : this; }
	else if(this.destroyed || this.disabled) { return this; }

	var type = state ? 'show' : 'hide',
		opts = this.options[type],
		otherOpts = this.options[ !state ? 'show' : 'hide' ],
		posOptions = this.options.position,
		contentOptions = this.options.content,
		width = this.tooltip.css('width'),
		visible = this.tooltip.is(':visible'),
		animate = state || opts.target.length === 1,
		sameTarget = !event || opts.target.length < 2 || cache.target[0] === event.target,
		identicalState, allow, showEvent, delay, after;

	// Detect state if valid one isn't provided
	if((typeof state).search('boolean|number')) { state = !visible; }

	// Check if the tooltip is in an identical state to the new would-be state
	identicalState = !tooltip.is(':animated') && visible === state && sameTarget;

	// Fire tooltip(show/hide) event and check if destroyed
	allow = !identicalState ? !!this._trigger(type, [90]) : NULL;

	// Check to make sure the tooltip wasn't destroyed in the callback
	if(this.destroyed) { return this; }

	// If the user didn't stop the method prematurely and we're showing the tooltip, focus it
	if(allow !== FALSE && state) { this.focus(event); }

	// If the state hasn't changed or the user stopped it, return early
	if(!allow || identicalState) { return this; }

	// Set ARIA hidden attribute
	$.attr(tooltip[0], 'aria-hidden', !!!state);

	// Execute state specific properties
	if(state) {
		// Store show origin coordinates
		cache.origin = cloneEvent(this.mouse);

		// Update tooltip content & title if it's a dynamic function
		if($.isFunction(contentOptions.text)) { this._updateContent(contentOptions.text, FALSE); }
		if($.isFunction(contentOptions.title)) { this._updateTitle(contentOptions.title, FALSE); }

		// Cache mousemove events for positioning purposes (if not already tracking)
		if(!trackingBound && posOptions.target === 'mouse' && posOptions.adjust.mouse) {
			$(document).bind('mousemove.'+NAMESPACE, this._storeMouse);
			trackingBound = TRUE;
		}

		// Update the tooltip position (set width first to prevent viewport/max-width issues)
		if(!width) { tooltip.css('width', tooltip.outerWidth(FALSE)); }
		this.reposition(event, arguments[2]);
		if(!width) { tooltip.css('width', ''); }

		// Hide other tooltips if tooltip is solo
		if(!!opts.solo) {
			(typeof opts.solo === 'string' ? $(opts.solo) : $(SELECTOR, opts.solo))
				.not(tooltip).not(opts.target).qtip('hide', $.Event('tooltipsolo'));
		}
	}
	else {
		// Clear show timer if we're hiding
		clearTimeout(this.timers.show);

		// Remove cached origin on hide
		delete cache.origin;

		// Remove mouse tracking event if not needed (all tracking qTips are hidden)
		if(trackingBound && !$(SELECTOR+'[tracking="true"]:visible', opts.solo).not(tooltip).length) {
			$(document).unbind('mousemove.'+NAMESPACE);
			trackingBound = FALSE;
		}

		// Blur the tooltip
		this.blur(event);
	}

	// Define post-animation, state specific properties
	after = $.proxy(function() {
		if(state) {
			// Prevent antialias from disappearing in IE by removing filter
			if(BROWSER.ie) { tooltip[0].style.removeAttribute('filter'); }

			// Remove overflow setting to prevent tip bugs
			tooltip.css('overflow', '');

			// Autofocus elements if enabled
			if('string' === typeof opts.autofocus) {
				$(this.options.show.autofocus, tooltip).focus();
			}

			// If set, hide tooltip when inactive for delay period
			this.options.show.target.trigger('qtip-'+this.id+'-inactive');
		}
		else {
			// Reset CSS states
			tooltip.css({
				display: '',
				visibility: '',
				opacity: '',
				left: '',
				top: ''
			});
		}

		// tooltipvisible/tooltiphidden events
		this._trigger(state ? 'visible' : 'hidden');
	}, this);

	// If no effect type is supplied, use a simple toggle
	if(opts.effect === FALSE || animate === FALSE) {
		tooltip[ type ]();
		after();
	}

	// Use custom function if provided
	else if($.isFunction(opts.effect)) {
		tooltip.stop(1, 1);
		opts.effect.call(tooltip, this);
		tooltip.queue('fx', function(n) {
			after(); n();
		});
	}

	// Use basic fade function by default
	else { tooltip.fadeTo(90, state ? 1 : 0, after); }

	// If inactive hide method is set, active it
	if(state) { opts.target.trigger('qtip-'+this.id+'-inactive'); }

	return this;
};

PROTOTYPE.show = function(event) { return this.toggle(TRUE, event); };

PROTOTYPE.hide = function(event) { return this.toggle(FALSE, event); };

;PROTOTYPE.focus = function(event) {
	if(!this.rendered || this.destroyed) { return this; }

	var qtips = $(SELECTOR),
		tooltip = this.tooltip,
		curIndex = parseInt(tooltip[0].style.zIndex, 10),
		newIndex = QTIP.zindex + qtips.length,
		focusedElem;

	// Only update the z-index if it has changed and tooltip is not already focused
	if(!tooltip.hasClass(CLASS_FOCUS)) {
		// tooltipfocus event
		if(this._trigger('focus', [newIndex], event)) {
			// Only update z-index's if they've changed
			if(curIndex !== newIndex) {
				// Reduce our z-index's and keep them properly ordered
				qtips.each(function() {
					if(this.style.zIndex > curIndex) {
						this.style.zIndex = this.style.zIndex - 1;
					}
				});

				// Fire blur event for focused tooltip
				qtips.filter('.' + CLASS_FOCUS).qtip('blur', event);
			}

			// Set the new z-index
			tooltip.addClass(CLASS_FOCUS)[0].style.zIndex = newIndex;
		}
	}

	return this;
};

PROTOTYPE.blur = function(event) {
	if(!this.rendered || this.destroyed) { return this; }

	// Set focused status to FALSE
	this.tooltip.removeClass(CLASS_FOCUS);

	// tooltipblur event
	this._trigger('blur', [ this.tooltip.css('zIndex') ], event);

	return this;
};

;PROTOTYPE.disable = function(state) {
	if(this.destroyed) { return this; }

	// If 'toggle' is passed, toggle the current state
	if(state === 'toggle') {
		state = !(this.rendered ? this.tooltip.hasClass(CLASS_DISABLED) : this.disabled);
	}

	// Disable if no state passed
	else if('boolean' !== typeof state) {
		state = TRUE;
	}

	if(this.rendered) {
		this.tooltip.toggleClass(CLASS_DISABLED, state)
			.attr('aria-disabled', state);
	}

	this.disabled = !!state;

	return this;
};

PROTOTYPE.enable = function() { return this.disable(FALSE); };

;PROTOTYPE._createButton = function()
{
	var self = this,
		elements = this.elements,
		tooltip = elements.tooltip,
		button = this.options.content.button,
		isString = typeof button === 'string',
		close = isString ? button : 'Close tooltip';

	if(elements.button) { elements.button.remove(); }

	// Use custom button if one was supplied by user, else use default
	if(button.jquery) {
		elements.button = button;
	}
	else {
		elements.button = $('<a />', {
			'class': 'qtip-close ' + (this.options.style.widget ? '' : NAMESPACE+'-icon'),
			'title': close,
			'aria-label': close
		})
		.prepend(
			$('<span />', {
				'class': 'ui-icon ui-icon-close',
				'html': '&times;'
			})
		);
	}

	// Create button and setup attributes
	elements.button.appendTo(elements.titlebar || tooltip)
		.attr('role', 'button')
		.click(function(event) {
			if(!tooltip.hasClass(CLASS_DISABLED)) { self.hide(event); }
			return FALSE;
		});
};

PROTOTYPE._updateButton = function(button)
{
	// Make sure tooltip is rendered and if not, return
	if(!this.rendered) { return FALSE; }

	var elem = this.elements.button;
	if(button) { this._createButton(); }
	else { elem.remove(); }
};

;// Widget class creator
function createWidgetClass(cls) {
	return WIDGET.concat('').join(cls ? '-'+cls+' ' : ' ');
}

// Widget class setter method
PROTOTYPE._setWidget = function()
{
	var on = this.options.style.widget,
		elements = this.elements,
		tooltip = elements.tooltip,
		disabled = tooltip.hasClass(CLASS_DISABLED);

	tooltip.removeClass(CLASS_DISABLED);
	CLASS_DISABLED = on ? 'ui-state-disabled' : 'qtip-disabled';
	tooltip.toggleClass(CLASS_DISABLED, disabled);

	tooltip.toggleClass('ui-helper-reset '+createWidgetClass(), on).toggleClass(CLASS_DEFAULT, this.options.style.def && !on);
	
	if(elements.content) {
		elements.content.toggleClass( createWidgetClass('content'), on);
	}
	if(elements.titlebar) {
		elements.titlebar.toggleClass( createWidgetClass('header'), on);
	}
	if(elements.button) {
		elements.button.toggleClass(NAMESPACE+'-icon', !on);
	}
};;function cloneEvent(event) {
	return event && {
		type: event.type,
		pageX: event.pageX,
		pageY: event.pageY,
		target: event.target,
		relatedTarget: event.relatedTarget,
		scrollX: event.scrollX || window.pageXOffset || document.body.scrollLeft || document.documentElement.scrollLeft,
		scrollY: event.scrollY || window.pageYOffset || document.body.scrollTop || document.documentElement.scrollTop
	} || {};
}

function delay(callback, duration) {
	// If tooltip has displayed, start hide timer
	if(duration > 0) {
		return setTimeout(
			$.proxy(callback, this), duration
		);
	}
	else{ callback.call(this); }
}

function showMethod(event) {
	if(this.tooltip.hasClass(CLASS_DISABLED)) { return FALSE; }

	// Clear hide timers
	clearTimeout(this.timers.show);
	clearTimeout(this.timers.hide);

	// Start show timer
	this.timers.show = delay.call(this,
		function() { this.toggle(TRUE, event); },
		this.options.show.delay
	);
}

function hideMethod(event) {
	if(this.tooltip.hasClass(CLASS_DISABLED)) { return FALSE; }

	// Check if new target was actually the tooltip element
	var relatedTarget = $(event.relatedTarget),
		ontoTooltip = relatedTarget.closest(SELECTOR)[0] === this.tooltip[0],
		ontoTarget = relatedTarget[0] === this.options.show.target[0];

	// Clear timers and stop animation queue
	clearTimeout(this.timers.show);
	clearTimeout(this.timers.hide);

	// Prevent hiding if tooltip is fixed and event target is the tooltip.
	// Or if mouse positioning is enabled and cursor momentarily overlaps
	if(this !== relatedTarget[0] && 
		(this.options.position.target === 'mouse' && ontoTooltip) || 
		(this.options.hide.fixed && (
			(/mouse(out|leave|move)/).test(event.type) && (ontoTooltip || ontoTarget))
		))
	{
		try {
			event.preventDefault();
			event.stopImmediatePropagation();
		} catch(e) {}

		return;
	}

	// If tooltip has displayed, start hide timer
	this.timers.hide = delay.call(this,
		function() { this.toggle(FALSE, event); },
		this.options.hide.delay,
		this
	);
}

function inactiveMethod(event) {
	if(this.tooltip.hasClass(CLASS_DISABLED) || !this.options.hide.inactive) { return FALSE; }

	// Clear timer
	clearTimeout(this.timers.inactive);

	this.timers.inactive = delay.call(this,
		function(){ this.hide(event); },
		this.options.hide.inactive
	);
}

function repositionMethod(event) {
	if(this.rendered && this.tooltip[0].offsetWidth > 0) { this.reposition(event); }
}

// Store mouse coordinates
PROTOTYPE._storeMouse = function(event) {
	(this.mouse = cloneEvent(event)).type = 'mousemove';
};

// Bind events
PROTOTYPE._bind = function(targets, events, method, suffix, context) {
	var ns = '.' + this._id + (suffix ? '-'+suffix : '');
	events.length && $(targets).bind(
		(events.split ? events : events.join(ns + ' ')) + ns,
		$.proxy(method, context || this)
	);
};
PROTOTYPE._unbind = function(targets, suffix) {
	$(targets).unbind('.' + this._id + (suffix ? '-'+suffix : ''));
};

// Apply common event handlers using delegate (avoids excessive .bind calls!)
var ns = '.'+NAMESPACE;
function delegate(selector, events, method) {	
	$(document.body).delegate(selector,
		(events.split ? events : events.join(ns + ' ')) + ns,
		function() {
			var api = QTIP.api[ $.attr(this, ATTR_ID) ];
			api && !api.disabled && method.apply(api, arguments);
		}
	);
}

$(function() {
	delegate(SELECTOR, ['mouseenter', 'mouseleave'], function(event) {
		var state = event.type === 'mouseenter',
			tooltip = $(event.currentTarget),
			target = $(event.relatedTarget || event.target),
			options = this.options;

		// On mouseenter...
		if(state) {
			// Focus the tooltip on mouseenter (z-index stacking)
			this.focus(event);

			// Clear hide timer on tooltip hover to prevent it from closing
			tooltip.hasClass(CLASS_FIXED) && !tooltip.hasClass(CLASS_DISABLED) && clearTimeout(this.timers.hide);
		}

		// On mouseleave...
		else {
			// Hide when we leave the tooltip and not onto the show target (if a hide event is set)
			if(options.position.target === 'mouse' && options.hide.event && 
				options.show.target && !target.closest(options.show.target[0]).length) {
				this.hide(event);
			}
		}

		// Add hover class
		tooltip.toggleClass(CLASS_HOVER, state);
	});

	// Define events which reset the 'inactive' event handler
	delegate('['+ATTR_ID+']', INACTIVE_EVENTS, inactiveMethod);
});

// Event trigger
PROTOTYPE._trigger = function(type, args, event) {
	var callback = $.Event('tooltip'+type);
	callback.originalEvent = (event && $.extend({}, event)) || this.cache.event || NULL;

	this.triggering = type;
	this.tooltip.trigger(callback, [this].concat(args || []));
	this.triggering = FALSE;

	return !callback.isDefaultPrevented();
};

PROTOTYPE._bindEvents = function(showEvents, hideEvents, showTarget, hideTarget, showMethod, hideMethod) {
	// If hide and show targets are the same...
	if(hideTarget.add(showTarget).length === hideTarget.length) {
		var toggleEvents = [];

		// Filter identical show/hide events
		hideEvents = $.map(hideEvents, function(type) {
			var showIndex = $.inArray(type, showEvents);

			// Both events are identical, remove from both hide and show events
			// and append to toggleEvents
			if(showIndex > -1) {
				toggleEvents.push( showEvents.splice( showIndex, 1 )[0] );
				return;
			}

			return type;
		});

		// Toggle events are special case of identical show/hide events, which happen in sequence
		toggleEvents.length && this._bind(showTarget, toggleEvents, function(event) {
			var state = this.rendered ? this.tooltip[0].offsetWidth > 0 : false;
			(state ? hideMethod : showMethod).call(this, event);
		});
	}

	// Apply show/hide/toggle events
	this._bind(showTarget, showEvents, showMethod);
	this._bind(hideTarget, hideEvents, hideMethod);
};

PROTOTYPE._assignInitialEvents = function(event) {
	var options = this.options,
		showTarget = options.show.target,
		hideTarget = options.hide.target,
		showEvents = options.show.event ? $.trim('' + options.show.event).split(' ') : [],
		hideEvents = options.hide.event ? $.trim('' + options.hide.event).split(' ') : [];

	/*
	 * Make sure hoverIntent functions properly by using mouseleave as a hide event if
	 * mouseenter/mouseout is used for show.event, even if it isn't in the users options.
	 */
	if(/mouse(over|enter)/i.test(options.show.event) && !/mouse(out|leave)/i.test(options.hide.event)) {
		hideEvents.push('mouseleave');
	}

	/*
	 * Also make sure initial mouse targetting works correctly by caching mousemove coords
	 * on show targets before the tooltip has rendered. Also set onTarget when triggered to
	 * keep mouse tracking working.
	 */
	this._bind(showTarget, 'mousemove', function(event) {
		this._storeMouse(event);
		this.cache.onTarget = TRUE;
	});

	// Define hoverIntent function
	function hoverIntent(event) {
		// Only continue if tooltip isn't disabled
		if(this.disabled || this.destroyed) { return FALSE; }

		// Cache the event data
		this.cache.event = cloneEvent(event);
		this.cache.target = event ? $(event.target) : [undefined];

		// Start the event sequence
		clearTimeout(this.timers.show);
		this.timers.show = delay.call(this,
			function() { this.render(typeof event === 'object' || options.show.ready); },
			options.show.delay
		);
	}

	// Filter and bind events
	this._bindEvents(showEvents, hideEvents, showTarget, hideTarget, hoverIntent, function() {
		clearTimeout(this.timers.show);
	});

	// Prerendering is enabled, create tooltip now
	if(options.show.ready || options.prerender) { hoverIntent.call(this, event); }
};

// Event assignment method
PROTOTYPE._assignEvents = function() {
	var self = this,
		options = this.options,
		posOptions = options.position,

		tooltip = this.tooltip,
		showTarget = options.show.target,
		hideTarget = options.hide.target,
		containerTarget = posOptions.container,
		viewportTarget = posOptions.viewport,
		documentTarget = $(document),
		bodyTarget = $(document.body),
		windowTarget = $(window),

		showEvents = options.show.event ? $.trim('' + options.show.event).split(' ') : [],
		hideEvents = options.hide.event ? $.trim('' + options.hide.event).split(' ') : [];


	// Assign passed event callbacks
	$.each(options.events, function(name, callback) {
		self._bind(tooltip, name === 'toggle' ? ['tooltipshow','tooltiphide'] : ['tooltip'+name], callback, null, tooltip);
	});

	// Hide tooltips when leaving current window/frame (but not select/option elements)
	if(/mouse(out|leave)/i.test(options.hide.event) && options.hide.leave === 'window') {
		this._bind(documentTarget, ['mouseout', 'blur'], function(event) {
			if(!/select|option/.test(event.target.nodeName) && !event.relatedTarget) {
				this.hide(event);
			}
		});
	}

	// Enable hide.fixed by adding appropriate class
	if(options.hide.fixed) {
		hideTarget = hideTarget.add( tooltip.addClass(CLASS_FIXED) );
	}

	/*
	 * Make sure hoverIntent functions properly by using mouseleave to clear show timer if
	 * mouseenter/mouseout is used for show.event, even if it isn't in the users options.
	 */
	else if(/mouse(over|enter)/i.test(options.show.event)) {
		this._bind(hideTarget, 'mouseleave', function() {
			clearTimeout(this.timers.show);
		});
	}

	// Hide tooltip on document mousedown if unfocus events are enabled
	if(('' + options.hide.event).indexOf('unfocus') > -1) {
		this._bind(containerTarget.closest('html'), ['mousedown', 'touchstart'], function(event) {
			var elem = $(event.target),
				enabled = this.rendered && !this.tooltip.hasClass(CLASS_DISABLED) && this.tooltip[0].offsetWidth > 0,
				isAncestor = elem.parents(SELECTOR).filter(this.tooltip[0]).length > 0;

			if(elem[0] !== this.target[0] && elem[0] !== this.tooltip[0] && !isAncestor &&
				!this.target.has(elem[0]).length && enabled
			) {
				this.hide(event);
			}
		});
	}

	// Check if the tooltip hides when inactive
	if('number' === typeof options.hide.inactive) {
		// Bind inactive method to show target(s) as a custom event
		this._bind(showTarget, 'qtip-'+this.id+'-inactive', inactiveMethod);

		// Define events which reset the 'inactive' event handler
		this._bind(hideTarget.add(tooltip), QTIP.inactiveEvents, inactiveMethod, '-inactive');
	}

	// Filter and bind events
	this._bindEvents(showEvents, hideEvents, showTarget, hideTarget, showMethod, hideMethod);

	// Mouse movement bindings
	this._bind(showTarget.add(tooltip), 'mousemove', function(event) {
		// Check if the tooltip hides when mouse is moved a certain distance
		if('number' === typeof options.hide.distance) {
			var origin = this.cache.origin || {},
				limit = this.options.hide.distance,
				abs = Math.abs;

			// Check if the movement has gone beyond the limit, and hide it if so
			if(abs(event.pageX - origin.pageX) >= limit || abs(event.pageY - origin.pageY) >= limit) {
				this.hide(event);
			}
		}

		// Cache mousemove coords on show targets
		this._storeMouse(event);
	});

	// Mouse positioning events
	if(posOptions.target === 'mouse') {
		// If mouse adjustment is on...
		if(posOptions.adjust.mouse) {
			// Apply a mouseleave event so we don't get problems with overlapping
			if(options.hide.event) {
				// Track if we're on the target or not
				this._bind(showTarget, ['mouseenter', 'mouseleave'], function(event) {
					this.cache.onTarget = event.type === 'mouseenter';
				});
			}

			// Update tooltip position on mousemove
			this._bind(documentTarget, 'mousemove', function(event) {
				// Update the tooltip position only if the tooltip is visible and adjustment is enabled
				if(this.rendered && this.cache.onTarget && !this.tooltip.hasClass(CLASS_DISABLED) && this.tooltip[0].offsetWidth > 0) {
					this.reposition(event);
				}
			});
		}
	}

	// Adjust positions of the tooltip on window resize if enabled
	if(posOptions.adjust.resize || viewportTarget.length) {
		this._bind( $.event.special.resize ? viewportTarget : windowTarget, 'resize', repositionMethod );
	}

	// Adjust tooltip position on scroll of the window or viewport element if present
	if(posOptions.adjust.scroll) {
		this._bind( windowTarget.add(posOptions.container), 'scroll', repositionMethod );
	}
};

// Un-assignment method
PROTOTYPE._unassignEvents = function() {
	var targets = [
		this.options.show.target[0],
		this.options.hide.target[0],
		this.rendered && this.tooltip[0],
		this.options.position.container[0],
		this.options.position.viewport[0],
		this.options.position.container.closest('html')[0], // unfocus
		window,
		document
	];

	this._unbind($([]).pushStack( $.grep(targets, function(i) {
		return typeof i === 'object';
	})));
};

;// Initialization method
function init(elem, id, opts) {
	var obj, posOptions, attr, config, title,

	// Setup element references
	docBody = $(document.body),

	// Use document body instead of document element if needed
	newTarget = elem[0] === document ? docBody : elem,

	// Grab metadata from element if plugin is present
	metadata = (elem.metadata) ? elem.metadata(opts.metadata) : NULL,

	// If metadata type if HTML5, grab 'name' from the object instead, or use the regular data object otherwise
	metadata5 = opts.metadata.type === 'html5' && metadata ? metadata[opts.metadata.name] : NULL,

	// Grab data from metadata.name (or data-qtipopts as fallback) using .data() method,
	html5 = elem.data(opts.metadata.name || 'qtipopts');

	// If we don't get an object returned attempt to parse it manualyl without parseJSON
	try { html5 = typeof html5 === 'string' ? $.parseJSON(html5) : html5; } catch(e) {}

	// Merge in and sanitize metadata
	config = $.extend(TRUE, {}, QTIP.defaults, opts,
		typeof html5 === 'object' ? sanitizeOptions(html5) : NULL,
		sanitizeOptions(metadata5 || metadata));

	// Re-grab our positioning options now we've merged our metadata and set id to passed value
	posOptions = config.position;
	config.id = id;

	// Setup missing content if none is detected
	if('boolean' === typeof config.content.text) {
		attr = elem.attr(config.content.attr);

		// Grab from supplied attribute if available
		if(config.content.attr !== FALSE && attr) { config.content.text = attr; }

		// No valid content was found, abort render
		else { return FALSE; }
	}

	// Setup target options
	if(!posOptions.container.length) { posOptions.container = docBody; }
	if(posOptions.target === FALSE) { posOptions.target = newTarget; }
	if(config.show.target === FALSE) { config.show.target = newTarget; }
	if(config.show.solo === TRUE) { config.show.solo = posOptions.container.closest('body'); }
	if(config.hide.target === FALSE) { config.hide.target = newTarget; }
	if(config.position.viewport === TRUE) { config.position.viewport = posOptions.container; }

	// Ensure we only use a single container
	posOptions.container = posOptions.container.eq(0);

	// Convert position corner values into x and y strings
	posOptions.at = new CORNER(posOptions.at, TRUE);
	posOptions.my = new CORNER(posOptions.my);

	// Destroy previous tooltip if overwrite is enabled, or skip element if not
	if(elem.data(NAMESPACE)) {
		if(config.overwrite) {
			elem.qtip('destroy', true);
		}
		else if(config.overwrite === FALSE) {
			return FALSE;
		}
	}

	// Add has-qtip attribute
	elem.attr(ATTR_HAS, id);

	// Remove title attribute and store it if present
	if(config.suppress && (title = elem.attr('title'))) {
		// Final attr call fixes event delegatiom and IE default tooltip showing problem
		elem.removeAttr('title').attr(oldtitle, title).attr('title', '');
	}

	// Initialize the tooltip and add API reference
	obj = new QTip(elem, config, id, !!attr);
	elem.data(NAMESPACE, obj);

	// Catch remove/removeqtip events on target element to destroy redundant tooltip
	elem.one('remove.qtip-'+id+' removeqtip.qtip-'+id, function() { 
		var api; if((api = $(this).data(NAMESPACE))) { api.destroy(true); }
	});

	return obj;
}

// jQuery $.fn extension method
QTIP = $.fn.qtip = function(options, notation, newValue)
{
	var command = ('' + options).toLowerCase(), // Parse command
		returned = NULL,
		args = $.makeArray(arguments).slice(1),
		event = args[args.length - 1],
		opts = this[0] ? $.data(this[0], NAMESPACE) : NULL;

	// Check for API request
	if((!arguments.length && opts) || command === 'api') {
		return opts;
	}

	// Execute API command if present
	else if('string' === typeof options) {
		this.each(function() {
			var api = $.data(this, NAMESPACE);
			if(!api) { return TRUE; }

			// Cache the event if possible
			if(event && event.timeStamp) { api.cache.event = event; }

			// Check for specific API commands
			if(notation && (command === 'option' || command === 'options')) {
				if(newValue !== undefined || $.isPlainObject(notation)) {
					api.set(notation, newValue);
				}
				else {
					returned = api.get(notation);
					return FALSE;
				}
			}

			// Execute API command
			else if(api[command]) {
				api[command].apply(api, args);
			}
		});

		return returned !== NULL ? returned : this;
	}

	// No API commands. validate provided options and setup qTips
	else if('object' === typeof options || !arguments.length) {
		// Sanitize options first
		opts = sanitizeOptions($.extend(TRUE, {}, options));

		return this.each(function(i) {
			var api, id;

			// Find next available ID, or use custom ID if provided
			id = $.isArray(opts.id) ? opts.id[i] : opts.id;
			id = !id || id === FALSE || id.length < 1 || QTIP.api[id] ? QTIP.nextid++ : id;

			// Initialize the qTip and re-grab newly sanitized options
			api = init($(this), id, opts);
			if(api === FALSE) { return TRUE; }
			else { QTIP.api[id] = api; }

			// Initialize plugins
			$.each(PLUGINS, function() {
				if(this.initialize === 'initialize') { this(api); }
			});

			// Assign initial pre-render events
			api._assignInitialEvents(event);
		});
	}
};

// Expose class
$.qtip = QTip;

// Populated in render method
QTIP.api = {};
;$.each({
	/* Allow other plugins to successfully retrieve the title of an element with a qTip applied */
	attr: function(attr, val) {
		if(this.length) {
			var self = this[0],
				title = 'title',
				api = $.data(self, 'qtip');

			if(attr === title && api && 'object' === typeof api && api.options.suppress) {
				if(arguments.length < 2) {
					return $.attr(self, oldtitle);
				}

				// If qTip is rendered and title was originally used as content, update it
				if(api && api.options.content.attr === title && api.cache.attr) {
					api.set('content.text', val);
				}

				// Use the regular attr method to set, then cache the result
				return this.attr(oldtitle, val);
			}
		}

		return $.fn['attr'+replaceSuffix].apply(this, arguments);
	},

	/* Allow clone to correctly retrieve cached title attributes */
	clone: function(keepData) {
		var titles = $([]), title = 'title',

		// Clone our element using the real clone method
		elems = $.fn['clone'+replaceSuffix].apply(this, arguments);

		// Grab all elements with an oldtitle set, and change it to regular title attribute, if keepData is false
		if(!keepData) {
			elems.filter('['+oldtitle+']').attr('title', function() {
				return $.attr(this, oldtitle);
			})
			.removeAttr(oldtitle);
		}

		return elems;
	}
}, function(name, func) {
	if(!func || $.fn[name+replaceSuffix]) { return TRUE; }

	var old = $.fn[name+replaceSuffix] = $.fn[name];
	$.fn[name] = function() {
		return func.apply(this, arguments) || old.apply(this, arguments);
	};
});

/* Fire off 'removeqtip' handler in $.cleanData if jQuery UI not present (it already does similar).
 * This snippet is taken directly from jQuery UI source code found here:
 *     http://code.jquery.com/ui/jquery-ui-git.js
 */
if(!$.ui) {
	$['cleanData'+replaceSuffix] = $.cleanData;
	$.cleanData = function( elems ) {
		for(var i = 0, elem; (elem = $( elems[i] )).length; i++) {
			if(elem.attr(ATTR_HAS)) {
				try { elem.triggerHandler('removeqtip'); } 
				catch( e ) {}
			}
		}
		$['cleanData'+replaceSuffix].apply(this, arguments);
	};
}

;// qTip version
QTIP.version = '2.2.0';

// Base ID for all qTips
QTIP.nextid = 0;

// Inactive events array
QTIP.inactiveEvents = INACTIVE_EVENTS;

// Base z-index for all qTips
QTIP.zindex = 15000;

// Define configuration defaults
QTIP.defaults = {
	prerender: FALSE,
	id: FALSE,
	overwrite: TRUE,
	suppress: TRUE,
	content: {
		text: TRUE,
		attr: 'title',
		title: FALSE,
		button: FALSE
	},
	position: {
		my: 'top left',
		at: 'bottom right',
		target: FALSE,
		container: FALSE,
		viewport: FALSE,
		adjust: {
			x: 0, y: 0,
			mouse: TRUE,
			scroll: TRUE,
			resize: TRUE,
			method: 'flipinvert flipinvert'
		},
		effect: function(api, pos, viewport) {
			$(this).animate(pos, {
				duration: 200,
				queue: FALSE
			});
		}
	},
	show: {
		target: FALSE,
		event: 'mouseenter',
		effect: TRUE,
		delay: 90,
		solo: FALSE,
		ready: FALSE,
		autofocus: FALSE
	},
	hide: {
		target: FALSE,
		event: 'mouseleave',
		effect: TRUE,
		delay: 0,
		fixed: FALSE,
		inactive: FALSE,
		leave: 'window',
		distance: FALSE
	},
	style: {
		classes: '',
		widget: FALSE,
		width: FALSE,
		height: FALSE,
		def: TRUE
	},
	events: {
		render: NULL,
		move: NULL,
		show: NULL,
		hide: NULL,
		toggle: NULL,
		visible: NULL,
		hidden: NULL,
		focus: NULL,
		blur: NULL
	}
};

;var TIP, 

// .bind()/.on() namespace
TIPNS = '.qtip-tip',

// Common CSS strings
MARGIN = 'margin',
BORDER = 'border',
COLOR = 'color',
BG_COLOR = 'background-color',
TRANSPARENT = 'transparent',
IMPORTANT = ' !important',

// Check if the browser supports <canvas/> elements
HASCANVAS = !!document.createElement('canvas').getContext,

// Invalid colour values used in parseColours()
INVALID = /rgba?\(0, 0, 0(, 0)?\)|transparent|#123456/i;

// Camel-case method, taken from jQuery source
// http://code.jquery.com/jquery-1.8.0.js
function camel(s) { return s.charAt(0).toUpperCase() + s.slice(1); }

/*
 * Modified from Modernizr's testPropsAll()
 * http://modernizr.com/downloads/modernizr-latest.js
 */
var cssProps = {}, cssPrefixes = ["Webkit", "O", "Moz", "ms"];
function vendorCss(elem, prop) {
	var ucProp = prop.charAt(0).toUpperCase() + prop.slice(1),
		props = (prop + ' ' + cssPrefixes.join(ucProp + ' ') + ucProp).split(' '),
		cur, val, i = 0;

	// If the property has already been mapped...
	if(cssProps[prop]) { return elem.css(cssProps[prop]); }

	while((cur = props[i++])) {
		if((val = elem.css(cur)) !== undefined) {
			return cssProps[prop] = cur, val;
		}
	}
}

// Parse a given elements CSS property into an int
function intCss(elem, prop) {
	return Math.ceil(parseFloat(vendorCss(elem, prop)));
}


// VML creation (for IE only)
if(!HASCANVAS) {
	var createVML = function(tag, props, style) {
		return '<qtipvml:'+tag+' xmlns="urn:schemas-microsoft.com:vml" class="qtip-vml" '+(props||'')+
			' style="behavior: url(#default#VML); '+(style||'')+ '" />';
	};
}

// Canvas only definitions
else {
	var PIXEL_RATIO = window.devicePixelRatio || 1,
		BACKING_STORE_RATIO = (function() {
			var context = document.createElement('canvas').getContext('2d');
			return context.backingStorePixelRatio || context.webkitBackingStorePixelRatio || context.mozBackingStorePixelRatio || 
					context.msBackingStorePixelRatio || context.oBackingStorePixelRatio || 1;
		}()),
		SCALE = PIXEL_RATIO / BACKING_STORE_RATIO;
}


function Tip(qtip, options) {
	this._ns = 'tip';
	this.options = options;
	this.offset = options.offset;
	this.size = [ options.width, options.height ];

	// Initialize
	this.init( (this.qtip = qtip) );
}

$.extend(Tip.prototype, {
	init: function(qtip) {
		var context, tip;

		// Create tip element and prepend to the tooltip
		tip = this.element = qtip.elements.tip = $('<div />', { 'class': NAMESPACE+'-tip' }).prependTo(qtip.tooltip);

		// Create tip drawing element(s)
		if(HASCANVAS) {
			// save() as soon as we create the canvas element so FF2 doesn't bork on our first restore()!
			context = $('<canvas />').appendTo(this.element)[0].getContext('2d');

			// Setup constant parameters
			context.lineJoin = 'miter';
			context.miterLimit = 100000;
			context.save();
		}
		else {
			context = createVML('shape', 'coordorigin="0,0"', 'position:absolute;');
			this.element.html(context + context);

			// Prevent mousing down on the tip since it causes problems with .live() handling in IE due to VML
			qtip._bind( $('*', tip).add(tip), ['click', 'mousedown'], function(event) { event.stopPropagation(); }, this._ns);
		}

		// Bind update events
		qtip._bind(qtip.tooltip, 'tooltipmove', this.reposition, this._ns, this);

		// Create it
		this.create();
	},

	_swapDimensions: function() {
		this.size[0] = this.options.height;
		this.size[1] = this.options.width;
	},
	_resetDimensions: function() {
		this.size[0] = this.options.width;
		this.size[1] = this.options.height;
	},

	_useTitle: function(corner) {
		var titlebar = this.qtip.elements.titlebar;
		return titlebar && (
			corner.y === TOP || (corner.y === CENTER && this.element.position().top + (this.size[1] / 2) + this.options.offset < titlebar.outerHeight(TRUE))
		);
	},

	_parseCorner: function(corner) {
		var my = this.qtip.options.position.my;

		// Detect corner and mimic properties
		if(corner === FALSE || my === FALSE) {
			corner = FALSE;
		}
		else if(corner === TRUE) {
			corner = new CORNER( my.string() );
		}
		else if(!corner.string) {
			corner = new CORNER(corner);
			corner.fixed = TRUE;
		}

		return corner;
	},

	_parseWidth: function(corner, side, use) {
		var elements = this.qtip.elements,
			prop = BORDER + camel(side) + 'Width';

		return (use ? intCss(use, prop) : (
			intCss(elements.content, prop) ||
			intCss(this._useTitle(corner) && elements.titlebar || elements.content, prop) ||
			intCss(elements.tooltip, prop)
		)) || 0;
	},

	_parseRadius: function(corner) {
		var elements = this.qtip.elements,
			prop = BORDER + camel(corner.y) + camel(corner.x) + 'Radius';

		return BROWSER.ie < 9 ? 0 :
			intCss(this._useTitle(corner) && elements.titlebar || elements.content, prop) || 
			intCss(elements.tooltip, prop) || 0;
	},

	_invalidColour: function(elem, prop, compare) {
		var val = elem.css(prop);
		return !val || (compare && val === elem.css(compare)) || INVALID.test(val) ? FALSE : val;
	},

	_parseColours: function(corner) {
		var elements = this.qtip.elements,
			tip = this.element.css('cssText', ''),
			borderSide = BORDER + camel(corner[ corner.precedance ]) + camel(COLOR),
			colorElem = this._useTitle(corner) && elements.titlebar || elements.content,
			css = this._invalidColour, color = [];

		// Attempt to detect the background colour from various elements, left-to-right precedance
		color[0] = css(tip, BG_COLOR) || css(colorElem, BG_COLOR) || css(elements.content, BG_COLOR) || 
			css(elements.tooltip, BG_COLOR) || tip.css(BG_COLOR);

		// Attempt to detect the correct border side colour from various elements, left-to-right precedance
		color[1] = css(tip, borderSide, COLOR) || css(colorElem, borderSide, COLOR) || 
			css(elements.content, borderSide, COLOR) || css(elements.tooltip, borderSide, COLOR) || elements.tooltip.css(borderSide);

		// Reset background and border colours
		$('*', tip).add(tip).css('cssText', BG_COLOR+':'+TRANSPARENT+IMPORTANT+';'+BORDER+':0'+IMPORTANT+';');

		return color;
	},

	_calculateSize: function(corner) {
		var y = corner.precedance === Y,
			width = this.options['width'],
			height = this.options['height'],
			isCenter = corner.abbrev() === 'c',
			base = (y ? width: height) * (isCenter ? 0.5 : 1),
			pow = Math.pow,
			round = Math.round,
			bigHyp, ratio, result,

		smallHyp = Math.sqrt( pow(base, 2) + pow(height, 2) ),
		hyp = [ (this.border / base) * smallHyp, (this.border / height) * smallHyp ];

		hyp[2] = Math.sqrt( pow(hyp[0], 2) - pow(this.border, 2) );
		hyp[3] = Math.sqrt( pow(hyp[1], 2) - pow(this.border, 2) );

		bigHyp = smallHyp + hyp[2] + hyp[3] + (isCenter ? 0 : hyp[0]);
		ratio = bigHyp / smallHyp;

		result = [ round(ratio * width), round(ratio * height) ];
		return y ? result : result.reverse();
	},

	// Tip coordinates calculator
	_calculateTip: function(corner, size, scale) {
		scale = scale || 1;
		size = size || this.size;

		var width = size[0] * scale,
			height = size[1] * scale,
			width2 = Math.ceil(width / 2), height2 = Math.ceil(height / 2),

		// Define tip coordinates in terms of height and width values
		tips = {
			br:	[0,0,		width,height,	width,0],
			bl:	[0,0,		width,0,		0,height],
			tr:	[0,height,	width,0,		width,height],
			tl:	[0,0,		0,height,		width,height],
			tc:	[0,height,	width2,0,		width,height],
			bc:	[0,0,		width,0,		width2,height],
			rc:	[0,0,		width,height2,	0,height],
			lc:	[width,0,	width,height,	0,height2]
		};

		// Set common side shapes
		tips.lt = tips.br; tips.rt = tips.bl;
		tips.lb = tips.tr; tips.rb = tips.tl;

		return tips[ corner.abbrev() ];
	},

	// Tip coordinates drawer (canvas)
	_drawCoords: function(context, coords) {
		context.beginPath();
		context.moveTo(coords[0], coords[1]);
		context.lineTo(coords[2], coords[3]);
		context.lineTo(coords[4], coords[5]);
		context.closePath();
	},

	create: function() {
		// Determine tip corner
		var c = this.corner = (HASCANVAS || BROWSER.ie) && this._parseCorner(this.options.corner);
		
		// If we have a tip corner...
		if( (this.enabled = !!this.corner && this.corner.abbrev() !== 'c') ) {
			// Cache it
			this.qtip.cache.corner = c.clone();

			// Create it
			this.update();
		}

		// Toggle tip element
		this.element.toggle(this.enabled);

		return this.corner;
	},

	update: function(corner, position) {
		if(!this.enabled) { return this; }

		var elements = this.qtip.elements,
			tip = this.element,
			inner = tip.children(),
			options = this.options,
			curSize = this.size,
			mimic = options.mimic,
			round = Math.round,
			color, precedance, context,
			coords, bigCoords, translate, newSize, border, BACKING_STORE_RATIO;

		// Re-determine tip if not already set
		if(!corner) { corner = this.qtip.cache.corner || this.corner; }

		// Use corner property if we detect an invalid mimic value
		if(mimic === FALSE) { mimic = corner; }

		// Otherwise inherit mimic properties from the corner object as necessary
		else {
			mimic = new CORNER(mimic);
			mimic.precedance = corner.precedance;

			if(mimic.x === 'inherit') { mimic.x = corner.x; }
			else if(mimic.y === 'inherit') { mimic.y = corner.y; }
			else if(mimic.x === mimic.y) {
				mimic[ corner.precedance ] = corner[ corner.precedance ];
			}
		}
		precedance = mimic.precedance;

		// Ensure the tip width.height are relative to the tip position
		if(corner.precedance === X) { this._swapDimensions(); }
		else { this._resetDimensions(); }

		// Update our colours
		color = this.color = this._parseColours(corner);

		// Detect border width, taking into account colours
		if(color[1] !== TRANSPARENT) {
			// Grab border width
			border = this.border = this._parseWidth(corner, corner[corner.precedance]);

			// If border width isn't zero, use border color as fill if it's not invalid (1.0 style tips)
			if(options.border && border < 1 && !INVALID.test(color[1])) { color[0] = color[1]; }

			// Set border width (use detected border width if options.border is true)
			this.border = border = options.border !== TRUE ? options.border : border;
		}

		// Border colour was invalid, set border to zero
		else { this.border = border = 0; }

		// Determine tip size
		newSize = this.size = this._calculateSize(corner);
		tip.css({
			width: newSize[0],
			height: newSize[1],
			lineHeight: newSize[1]+'px'
		});

		// Calculate tip translation
		if(corner.precedance === Y) {
			translate = [
				round(mimic.x === LEFT ? border : mimic.x === RIGHT ? newSize[0] - curSize[0] - border : (newSize[0] - curSize[0]) / 2),
				round(mimic.y === TOP ? newSize[1] - curSize[1] : 0)
			];
		}
		else {
			translate = [
				round(mimic.x === LEFT ? newSize[0] - curSize[0] : 0),
				round(mimic.y === TOP ? border : mimic.y === BOTTOM ? newSize[1] - curSize[1] - border : (newSize[1] - curSize[1]) / 2)
			];
		}

		// Canvas drawing implementation
		if(HASCANVAS) {
			// Grab canvas context and clear/save it
			context = inner[0].getContext('2d');
			context.restore(); context.save();
			context.clearRect(0,0,6000,6000);
			
			// Calculate coordinates
			coords = this._calculateTip(mimic, curSize, SCALE);
			bigCoords = this._calculateTip(mimic, this.size, SCALE);

			// Set the canvas size using calculated size
			inner.attr(WIDTH, newSize[0] * SCALE).attr(HEIGHT, newSize[1] * SCALE);
			inner.css(WIDTH, newSize[0]).css(HEIGHT, newSize[1]);

			// Draw the outer-stroke tip
			this._drawCoords(context, bigCoords);
			context.fillStyle = color[1];
			context.fill();

			// Draw the actual tip
			context.translate(translate[0] * SCALE, translate[1] * SCALE);
			this._drawCoords(context, coords);
			context.fillStyle = color[0];
			context.fill();
		}

		// VML (IE Proprietary implementation)
		else {
			// Calculate coordinates
			coords = this._calculateTip(mimic);

			// Setup coordinates string
			coords = 'm' + coords[0] + ',' + coords[1] + ' l' + coords[2] +
				',' + coords[3] + ' ' + coords[4] + ',' + coords[5] + ' xe';

			// Setup VML-specific offset for pixel-perfection
			translate[2] = border && /^(r|b)/i.test(corner.string()) ?
				BROWSER.ie === 8 ? 2 : 1 : 0;

			// Set initial CSS
			inner.css({
				coordsize: (newSize[0]+border) + ' ' + (newSize[1]+border),
				antialias: ''+(mimic.string().indexOf(CENTER) > -1),
				left: translate[0] - (translate[2] * Number(precedance === X)),
				top: translate[1] - (translate[2] * Number(precedance === Y)),
				width: newSize[0] + border,
				height: newSize[1] + border
			})
			.each(function(i) {
				var $this = $(this);

				// Set shape specific attributes
				$this[ $this.prop ? 'prop' : 'attr' ]({
					coordsize: (newSize[0]+border) + ' ' + (newSize[1]+border),
					path: coords,
					fillcolor: color[0],
					filled: !!i,
					stroked: !i
				})
				.toggle(!!(border || i));

				// Check if border is enabled and add stroke element
				!i && $this.html( createVML(
					'stroke', 'weight="'+(border*2)+'px" color="'+color[1]+'" miterlimit="1000" joinstyle="miter"'
				) );
			});
		}

		// Opera bug #357 - Incorrect tip position
		// https://github.com/Craga89/qTip2/issues/367
		window.opera && setTimeout(function() {
			elements.tip.css({
				display: 'inline-block',
				visibility: 'visible'
			});
		}, 1);

		// Position if needed
		if(position !== FALSE) { this.calculate(corner, newSize); }
	},

	calculate: function(corner, size) {
		if(!this.enabled) { return FALSE; }

		var self = this,
			elements = this.qtip.elements,
			tip = this.element,
			userOffset = this.options.offset,
			isWidget = elements.tooltip.hasClass('ui-widget'),
			position = {  },
			precedance, corners;

		// Inherit corner if not provided
		corner = corner || this.corner;
		precedance = corner.precedance;

		// Determine which tip dimension to use for adjustment
		size = size || this._calculateSize(corner);

		// Setup corners and offset array
		corners = [ corner.x, corner.y ];
		if(precedance === X) { corners.reverse(); }

		// Calculate tip position
		$.each(corners, function(i, side) {
			var b, bc, br;

			if(side === CENTER) {
				b = precedance === Y ? LEFT : TOP;
				position[ b ] = '50%';
				position[MARGIN+'-' + b] = -Math.round(size[ precedance === Y ? 0 : 1 ] / 2) + userOffset;
			}
			else {
				b = self._parseWidth(corner, side, elements.tooltip);
				bc = self._parseWidth(corner, side, elements.content);
				br = self._parseRadius(corner);

				position[ side ] = Math.max(-self.border, i ? bc : (userOffset + (br > b ? br : -b)));
			}
		});

		// Adjust for tip size
		position[ corner[precedance] ] -= size[ precedance === X ? 0 : 1 ];

		// Set and return new position
		tip.css({ margin: '', top: '', bottom: '', left: '', right: '' }).css(position);
		return position;
	},

	reposition: function(event, api, pos, viewport) {
		if(!this.enabled) { return; }

		var cache = api.cache,
			newCorner = this.corner.clone(),
			adjust = pos.adjusted,
			method = api.options.position.adjust.method.split(' '),
			horizontal = method[0],
			vertical = method[1] || method[0],
			shift = { left: FALSE, top: FALSE, x: 0, y: 0 },
			offset, css = {}, props;

		function shiftflip(direction, precedance, popposite, side, opposite) {
			// Horizontal - Shift or flip method
			if(direction === SHIFT && newCorner.precedance === precedance && adjust[side] && newCorner[popposite] !== CENTER) {
				newCorner.precedance = newCorner.precedance === X ? Y : X;
			}
			else if(direction !== SHIFT && adjust[side]){
				newCorner[precedance] = newCorner[precedance] === CENTER ? 
					(adjust[side] > 0 ? side : opposite) : (newCorner[precedance] === side ? opposite : side);
			}
		}

		function shiftonly(xy, side, opposite) {
			if(newCorner[xy] === CENTER) {
				css[MARGIN+'-'+side] = shift[xy] = offset[MARGIN+'-'+side] - adjust[side];
			}
			else {
				props = offset[opposite] !== undefined ?
					[ adjust[side], -offset[side] ] : [ -adjust[side], offset[side] ];

				if( (shift[xy] = Math.max(props[0], props[1])) > props[0] ) {
					pos[side] -= adjust[side];
					shift[side] = FALSE;
				}
				
				css[ offset[opposite] !== undefined ? opposite : side ] = shift[xy];
			}
		}

		// If our tip position isn't fixed e.g. doesn't adjust with viewport...
		if(this.corner.fixed !== TRUE) {
			// Perform shift/flip adjustments
			shiftflip(horizontal, X, Y, LEFT, RIGHT);
			shiftflip(vertical, Y, X, TOP, BOTTOM);

			// Update and redraw the tip if needed (check cached details of last drawn tip)
			if(newCorner.string() !== cache.corner.string() && (cache.cornerTop !== adjust.top || cache.cornerLeft !== adjust.left)) {
				this.update(newCorner, FALSE);
			}
		}

		// Setup tip offset properties
		offset = this.calculate(newCorner);

		// Readjust offset object to make it left/top
		if(offset.right !== undefined) { offset.left = -offset.right; }
		if(offset.bottom !== undefined) { offset.top = -offset.bottom; }
		offset.user = this.offset;

		// Perform shift adjustments
		if(shift.left = (horizontal === SHIFT && !!adjust.left)) { shiftonly(X, LEFT, RIGHT); }
		if(shift.top = (vertical === SHIFT && !!adjust.top)) { shiftonly(Y, TOP, BOTTOM); }

		/*
		* If the tip is adjusted in both dimensions, or in a
		* direction that would cause it to be anywhere but the
		* outer border, hide it!
		*/
		this.element.css(css).toggle(
			!((shift.x && shift.y) || (newCorner.x === CENTER && shift.y) || (newCorner.y === CENTER && shift.x))
		);

		// Adjust position to accomodate tip dimensions
		pos.left -= offset.left.charAt ? offset.user : 
			horizontal !== SHIFT || shift.top || !shift.left && !shift.top ? offset.left + this.border : 0;
		pos.top -= offset.top.charAt ? offset.user : 
			vertical !== SHIFT || shift.left || !shift.left && !shift.top ? offset.top + this.border : 0;

		// Cache details
		cache.cornerLeft = adjust.left; cache.cornerTop = adjust.top;
		cache.corner = newCorner.clone();
	},

	destroy: function() {
		// Unbind events
		this.qtip._unbind(this.qtip.tooltip, this._ns);

		// Remove the tip element(s)
		if(this.qtip.elements.tip) {
			this.qtip.elements.tip.find('*')
				.remove().end().remove();
		}
	}
});

TIP = PLUGINS.tip = function(api) {
	return new Tip(api, api.options.style.tip);
};

// Initialize tip on render
TIP.initialize = 'render';

// Setup plugin sanitization options
TIP.sanitize = function(options) {
	if(options.style && 'tip' in options.style) {
		var opts = options.style.tip;
		if(typeof opts !== 'object') { opts = options.style.tip = { corner: opts }; }
		if(!(/string|boolean/i).test(typeof opts.corner)) { opts.corner = TRUE; }
	}
};

// Add new option checks for the plugin
CHECKS.tip = {
	'^position.my|style.tip.(corner|mimic|border)$': function() {
		// Make sure a tip can be drawn
		this.create();
		
		// Reposition the tooltip
		this.qtip.reposition();
	},
	'^style.tip.(height|width)$': function(obj) {
		// Re-set dimensions and redraw the tip
		this.size = [ obj.width, obj.height ];
		this.update();

		// Reposition the tooltip
		this.qtip.reposition();
	},
	'^content.title|style.(classes|widget)$': function() {
		this.update();
	}
};

// Extend original qTip defaults
$.extend(TRUE, QTIP.defaults, {
	style: {
		tip: {
			corner: TRUE,
			mimic: FALSE,
			width: 6,
			height: 6,
			border: TRUE,
			offset: 0
		}
	}
});

;PLUGINS.polys = {
	// POLY area coordinate calculator
	//	Special thanks to Ed Cradock for helping out with this.
	//	Uses a binary search algorithm to find suitable coordinates.
	polygon: function(baseCoords, corner) {
		var result = {
			width: 0, height: 0,
			position: {
				top: 1e10, right: 0,
				bottom: 0, left: 1e10
			},
			adjustable: FALSE
		},
		i = 0, next,
		coords = [],
		compareX = 1, compareY = 1,
		realX = 0, realY = 0,
		newWidth, newHeight;

		// First pass, sanitize coords and determine outer edges
		i = baseCoords.length; while(i--) {
			next = [ parseInt(baseCoords[--i], 10), parseInt(baseCoords[i+1], 10) ];

			if(next[0] > result.position.right){ result.position.right = next[0]; }
			if(next[0] < result.position.left){ result.position.left = next[0]; }
			if(next[1] > result.position.bottom){ result.position.bottom = next[1]; }
			if(next[1] < result.position.top){ result.position.top = next[1]; }

			coords.push(next);
		}

		// Calculate height and width from outer edges
		newWidth = result.width = Math.abs(result.position.right - result.position.left);
		newHeight = result.height = Math.abs(result.position.bottom - result.position.top);

		// If it's the center corner...
		if(corner.abbrev() === 'c') {
			result.position = {
				left: result.position.left + (result.width / 2),
				top: result.position.top + (result.height / 2)
			};
		}
		else {
			// Second pass, use a binary search algorithm to locate most suitable coordinate
			while(newWidth > 0 && newHeight > 0 && compareX > 0 && compareY > 0)
			{
				newWidth = Math.floor(newWidth / 2);
				newHeight = Math.floor(newHeight / 2);

				if(corner.x === LEFT){ compareX = newWidth; }
				else if(corner.x === RIGHT){ compareX = result.width - newWidth; }
				else{ compareX += Math.floor(newWidth / 2); }

				if(corner.y === TOP){ compareY = newHeight; }
				else if(corner.y === BOTTOM){ compareY = result.height - newHeight; }
				else{ compareY += Math.floor(newHeight / 2); }

				i = coords.length; while(i--)
				{
					if(coords.length < 2){ break; }

					realX = coords[i][0] - result.position.left;
					realY = coords[i][1] - result.position.top;

					if((corner.x === LEFT && realX >= compareX) ||
					(corner.x === RIGHT && realX <= compareX) ||
					(corner.x === CENTER && (realX < compareX || realX > (result.width - compareX))) ||
					(corner.y === TOP && realY >= compareY) ||
					(corner.y === BOTTOM && realY <= compareY) ||
					(corner.y === CENTER && (realY < compareY || realY > (result.height - compareY)))) {
						coords.splice(i, 1);
					}
				}
			}
			result.position = { left: coords[0][0], top: coords[0][1] };
		}

		return result;
	},

	rect: function(ax, ay, bx, by) {
		return {
			width: Math.abs(bx - ax),
			height: Math.abs(by - ay),
			position: {
				left: Math.min(ax, bx),
				top: Math.min(ay, by)
			}
		};
	},

	_angles: {
		tc: 3 / 2, tr: 7 / 4, tl: 5 / 4, 
		bc: 1 / 2, br: 1 / 4, bl: 3 / 4, 
		rc: 2, lc: 1, c: 0
	},
	ellipse: function(cx, cy, rx, ry, corner) {
		var c = PLUGINS.polys._angles[ corner.abbrev() ],
			rxc = c === 0 ? 0 : rx * Math.cos( c * Math.PI ),
			rys = ry * Math.sin( c * Math.PI );

		return {
			width: (rx * 2) - Math.abs(rxc),
			height: (ry * 2) - Math.abs(rys),
			position: {
				left: cx + rxc,
				top: cy + rys
			},
			adjustable: FALSE
		};
	},
	circle: function(cx, cy, r, corner) {
		return PLUGINS.polys.ellipse(cx, cy, r, r, corner);
	}
};;PLUGINS.svg = function(api, svg, corner)
{
	var doc = $(document),
		elem = svg[0],
		root = $(elem.ownerSVGElement),
		xScale = 1, yScale = 1,
		complex = true,
		rootWidth, rootHeight,
		mtx, transformed, viewBox,
		len, next, i, points,
		result, position, dimensions;

	// Ascend the parentNode chain until we find an element with getBBox()
	while(!elem.getBBox) { elem = elem.parentNode; }
	if(!elem.getBBox || !elem.parentNode) { return FALSE; }

	// Determine dimensions where possible
	rootWidth = root.attr('width') || root.width() || parseInt(root.css('width'), 10);
	rootHeight = root.attr('height') || root.height() || parseInt(root.css('height'), 10);

	// Add stroke characteristics to scaling
	var strokeWidth2 = (parseInt(svg.css('stroke-width'), 10) || 0) / 2;
	if(strokeWidth2) {
		xScale += strokeWidth2 / rootWidth;
		yScale += strokeWidth2 / rootHeight;
	}

	// Determine which shape calculation to use
	switch(elem.nodeName) {
		case 'ellipse':
		case 'circle':
			result = PLUGINS.polys.ellipse(
				elem.cx.baseVal.value,
				elem.cy.baseVal.value,
				(elem.rx || elem.r).baseVal.value + strokeWidth2,
				(elem.ry || elem.r).baseVal.value + strokeWidth2,
				corner
			);
		break;

		case 'line':
		case 'polygon':
		case 'polyline':
			// Determine points object (line has none, so mimic using array)
			points = elem.points || [ 
				{ x: elem.x1.baseVal.value, y: elem.y1.baseVal.value },
				{ x: elem.x2.baseVal.value, y: elem.y2.baseVal.value }
			];

			for(result = [], i = -1, len = points.numberOfItems || points.length; ++i < len;) {
				next = points.getItem ? points.getItem(i) : points[i];
				result.push.apply(result, [next.x, next.y]);
			}

			result = PLUGINS.polys.polygon(result, corner);
		break;

		// Unknown shape or rectangle? Use bounding box
		default:
			result = elem.getBoundingClientRect();
			result = {
				width: result.width, height: result.height,
				position: {
					left: result.left,
					top: result.top
				}
			};
			complex = false;
		break;
	}

	// Shortcut assignments
	position = result.position;
	root = root[0];

	// If the shape was complex (i.e. not using bounding box calculations)
	if(complex) {
		// Convert position into a pixel value
		if(root.createSVGPoint) {
			mtx = elem.getScreenCTM();
			points = root.createSVGPoint();

			points.x = position.left;
			points.y = position.top;
			transformed = points.matrixTransform( mtx );
			position.left = transformed.x;
			position.top = transformed.y;
		}

		// Calculate viewBox characteristics
		if(root.viewBox && (viewBox = root.viewBox.baseVal) && viewBox.width && viewBox.height) {
			xScale *= rootWidth / viewBox.width;
			yScale *= rootHeight / viewBox.height;
		}
	}

	// Adjust by scroll offset
	position.left += doc.scrollLeft();
	position.top += doc.scrollTop();

	return result;
};;}));
}( window, document ));


/**
 * jquery.timer.js
 *
 * Copyright (c) 2011 Jason Chavannes <jason.chavannes@gmail.com>
 *
 * http://jchavannes.com/jquery-timer
 *
 * Permission is hereby granted, free of charge, to any person
 * obtaining a copy of this software and associated documentation
 * files (the "Software"), to deal in the Software without
 * restriction, including without limitation the rights to use, copy,
 * modify, merge, publish, distribute, sublicense, and/or sell copies
 * of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be
 * included in all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND,
 * EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF
 * MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND
 * NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS
 * BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN
 * ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN
 * CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
 * SOFTWARE.
 */

;(function($) {
	$.timer = function(func, time, autostart) {	
	 	this.set = function(func, time, autostart) {
	 		this.init = true;
	 	 	if(typeof func == 'object') {
		 	 	var paramList = ['autostart', 'time'];
	 	 	 	for(var arg in paramList) {if(func[paramList[arg]] != undefined) {eval(paramList[arg] + " = func[paramList[arg]]");}};
 	 			func = func.action;
	 	 	}
	 	 	if(typeof func == 'function') {this.action = func;}
		 	if(!isNaN(time)) {this.intervalTime = time;}
		 	if(autostart && !this.isActive) {
			 	this.isActive = true;
			 	this.setTimer();
		 	}
		 	return this;
	 	};
	 	this.once = function(time) {
			var timer = this;
	 	 	if(isNaN(time)) {time = 0;}
			window.setTimeout(function() {timer.action();}, time);
	 		return this;
	 	};
		this.play = function(reset) {
			if(!this.isActive) {
				if(reset) {this.setTimer();}
				else {this.setTimer(this.remaining);}
				this.isActive = true;
			}
			return this;
		};
		this.pause = function() {
			if(this.isActive) {
				this.isActive = false;
				this.remaining -= new Date() - this.last;
				this.clearTimer();
			}
			return this;
		};
		this.stop = function() {
			this.isActive = false;
			this.remaining = this.intervalTime;
			this.clearTimer();
			return this;
		};
		this.toggle = function(reset) {
			if(this.isActive) {this.pause();}
			else if(reset) {this.play(true);}
			else {this.play();}
			return this;
		};
		this.reset = function() {
			this.isActive = false;
			this.play(true);
			return this;
		};
		this.clearTimer = function() {
			window.clearTimeout(this.timeoutObject);
		};
	 	this.setTimer = function(time) {
			var timer = this;
	 	 	if(typeof this.action != 'function') {return;}
	 	 	if(isNaN(time)) {time = this.intervalTime;}
		 	this.remaining = time;
	 	 	this.last = new Date();
			this.clearTimer();
			this.timeoutObject = window.setTimeout(function() {timer.go();}, time);
		};
	 	this.go = function() {
	 		if(this.isActive) {
	 			this.action();
	 			this.setTimer();
	 		}
	 	};
	 	
	 	if(this.init) {
	 		return new $.timer(func, time, autostart);
	 	} else {
			this.set(func, time, autostart);
	 		return this;
	 	}
	};
})(jQuery);/*
    json2.js
    2012-10-08

    Public Domain.

    NO WARRANTY EXPRESSED OR IMPLIED. USE AT YOUR OWN RISK.

    See http://www.JSON.org/js.html


    This code should be minified before deployment.
    See http://javascript.crockford.com/jsmin.html

    USE YOUR OWN COPY. IT IS EXTREMELY UNWISE TO LOAD CODE FROM SERVERS YOU DO
    NOT CONTROL.


    This file creates a global JSON object containing two methods: stringify
    and parse.

        JSON.stringify(value, replacer, space)
            value       any JavaScript value, usually an object or array.

            replacer    an optional parameter that determines how object
                        values are stringified for objects. It can be a
                        function or an array of strings.

            space       an optional parameter that specifies the indentation
                        of nested structures. If it is omitted, the text will
                        be packed without extra whitespace. If it is a number,
                        it will specify the number of spaces to indent at each
                        level. If it is a string (such as '\t' or '&nbsp;'),
                        it contains the characters used to indent at each level.

            This method produces a JSON text from a JavaScript value.

            When an object value is found, if the object contains a toJSON
            method, its toJSON method will be called and the result will be
            stringified. A toJSON method does not serialize: it returns the
            value represented by the name/value pair that should be serialized,
            or undefined if nothing should be serialized. The toJSON method
            will be passed the key associated with the value, and this will be
            bound to the value

            For example, this would serialize Dates as ISO strings.

                Date.prototype.toJSON = function (key) {
                    function f(n) {
                        // Format integers to have at least two digits.
                        return n < 10 ? '0' + n : n;
                    }

                    return this.getUTCFullYear()   + '-' +
                         f(this.getUTCMonth() + 1) + '-' +
                         f(this.getUTCDate())      + 'T' +
                         f(this.getUTCHours())     + ':' +
                         f(this.getUTCMinutes())   + ':' +
                         f(this.getUTCSeconds())   + 'Z';
                };

            You can provide an optional replacer method. It will be passed the
            key and value of each member, with this bound to the containing
            object. The value that is returned from your method will be
            serialized. If your method returns undefined, then the member will
            be excluded from the serialization.

            If the replacer parameter is an array of strings, then it will be
            used to select the members to be serialized. It filters the results
            such that only members with keys listed in the replacer array are
            stringified.

            Values that do not have JSON representations, such as undefined or
            functions, will not be serialized. Such values in objects will be
            dropped; in arrays they will be replaced with null. You can use
            a replacer function to replace those with JSON values.
            JSON.stringify(undefined) returns undefined.

            The optional space parameter produces a stringification of the
            value that is filled with line breaks and indentation to make it
            easier to read.

            If the space parameter is a non-empty string, then that string will
            be used for indentation. If the space parameter is a number, then
            the indentation will be that many spaces.

            Example:

            text = JSON.stringify(['e', {pluribus: 'unum'}]);
            // text is '["e",{"pluribus":"unum"}]'


            text = JSON.stringify(['e', {pluribus: 'unum'}], null, '\t');
            // text is '[\n\t"e",\n\t{\n\t\t"pluribus": "unum"\n\t}\n]'

            text = JSON.stringify([new Date()], function (key, value) {
                return this[key] instanceof Date ?
                    'Date(' + this[key] + ')' : value;
            });
            // text is '["Date(---current time---)"]'


        JSON.parse(text, reviver)
            This method parses a JSON text to produce an object or array.
            It can throw a SyntaxError exception.

            The optional reviver parameter is a function that can filter and
            transform the results. It receives each of the keys and values,
            and its return value is used instead of the original value.
            If it returns what it received, then the structure is not modified.
            If it returns undefined then the member is deleted.

            Example:

            // Parse the text. Values that look like ISO date strings will
            // be converted to Date objects.

            myData = JSON.parse(text, function (key, value) {
                var a;
                if (typeof value === 'string') {
                    a =
/^(\d{4})-(\d{2})-(\d{2})T(\d{2}):(\d{2}):(\d{2}(?:\.\d*)?)Z$/.exec(value);
                    if (a) {
                        return new Date(Date.UTC(+a[1], +a[2] - 1, +a[3], +a[4],
                            +a[5], +a[6]));
                    }
                }
                return value;
            });

            myData = JSON.parse('["Date(09/09/2001)"]', function (key, value) {
                var d;
                if (typeof value === 'string' &&
                        value.slice(0, 5) === 'Date(' &&
                        value.slice(-1) === ')') {
                    d = new Date(value.slice(5, -1));
                    if (d) {
                        return d;
                    }
                }
                return value;
            });


    This is a reference implementation. You are free to copy, modify, or
    redistribute.
*/

/*jslint evil: true, regexp: true */

/*members "", "\b", "\t", "\n", "\f", "\r", "\"", JSON, "\\", apply,
    call, charCodeAt, getUTCDate, getUTCFullYear, getUTCHours,
    getUTCMinutes, getUTCMonth, getUTCSeconds, hasOwnProperty, join,
    lastIndex, length, parse, prototype, push, replace, slice, stringify,
    test, toJSON, toString, valueOf
*/


// Create a JSON object only if one does not already exist. We create the
// methods in a closure to avoid creating global variables.

if (typeof JSON !== 'object') {
    JSON = {};
}

(function () {
    'use strict';

    function f(n) {
        // Format integers to have at least two digits.
        return n < 10 ? '0' + n : n;
    }

    if (typeof Date.prototype.toJSON !== 'function') {

        Date.prototype.toJSON = function (key) {

            return isFinite(this.valueOf())
                ? this.getUTCFullYear()     + '-' +
                    f(this.getUTCMonth() + 1) + '-' +
                    f(this.getUTCDate())      + 'T' +
                    f(this.getUTCHours())     + ':' +
                    f(this.getUTCMinutes())   + ':' +
                    f(this.getUTCSeconds())   + 'Z'
                : null;
        };

        String.prototype.toJSON      =
            Number.prototype.toJSON  =
            Boolean.prototype.toJSON = function (key) {
                return this.valueOf();
            };
    }

    var cx = /[\u0000\u00ad\u0600-\u0604\u070f\u17b4\u17b5\u200c-\u200f\u2028-\u202f\u2060-\u206f\ufeff\ufff0-\uffff]/g,
        escapable = /[\\\"\x00-\x1f\x7f-\x9f\u00ad\u0600-\u0604\u070f\u17b4\u17b5\u200c-\u200f\u2028-\u202f\u2060-\u206f\ufeff\ufff0-\uffff]/g,
        gap,
        indent,
        meta = {    // table of character substitutions
            '\b': '\\b',
            '\t': '\\t',
            '\n': '\\n',
            '\f': '\\f',
            '\r': '\\r',
            '"' : '\\"',
            '\\': '\\\\'
        },
        rep;


    function quote(string) {

// If the string contains no control characters, no quote characters, and no
// backslash characters, then we can safely slap some quotes around it.
// Otherwise we must also replace the offending characters with safe escape
// sequences.

        escapable.lastIndex = 0;
        return escapable.test(string) ? '"' + string.replace(escapable, function (a) {
            var c = meta[a];
            return typeof c === 'string'
                ? c
                : '\\u' + ('0000' + a.charCodeAt(0).toString(16)).slice(-4);
        }) + '"' : '"' + string + '"';
    }


    function str(key, holder) {

// Produce a string from holder[key].

        var i,          // The loop counter.
            k,          // The member key.
            v,          // The member value.
            length,
            mind = gap,
            partial,
            value = holder[key];

// If the value has a toJSON method, call it to obtain a replacement value.

        if (value && typeof value === 'object' &&
                typeof value.toJSON === 'function') {
            value = value.toJSON(key);
        }

// If we were called with a replacer function, then call the replacer to
// obtain a replacement value.

        if (typeof rep === 'function') {
            value = rep.call(holder, key, value);
        }

// What happens next depends on the value's type.

        switch (typeof value) {
        case 'string':
            return quote(value);

        case 'number':

// JSON numbers must be finite. Encode non-finite numbers as null.

            return isFinite(value) ? String(value) : 'null';

        case 'boolean':
        case 'null':

// If the value is a boolean or null, convert it to a string. Note:
// typeof null does not produce 'null'. The case is included here in
// the remote chance that this gets fixed someday.

            return String(value);

// If the type is 'object', we might be dealing with an object or an array or
// null.

        case 'object':

// Due to a specification blunder in ECMAScript, typeof null is 'object',
// so watch out for that case.

            if (!value) {
                return 'null';
            }

// Make an array to hold the partial results of stringifying this object value.

            gap += indent;
            partial = [];

// Is the value an array?

            if (Object.prototype.toString.apply(value) === '[object Array]') {

// The value is an array. Stringify every element. Use null as a placeholder
// for non-JSON values.

                length = value.length;
                for (i = 0; i < length; i += 1) {
                    partial[i] = str(i, value) || 'null';
                }

// Join all of the elements together, separated with commas, and wrap them in
// brackets.

                v = partial.length === 0
                    ? '[]'
                    : gap
                    ? '[\n' + gap + partial.join(',\n' + gap) + '\n' + mind + ']'
                    : '[' + partial.join(',') + ']';
                gap = mind;
                return v;
            }

// If the replacer is an array, use it to select the members to be stringified.

            if (rep && typeof rep === 'object') {
                length = rep.length;
                for (i = 0; i < length; i += 1) {
                    if (typeof rep[i] === 'string') {
                        k = rep[i];
                        v = str(k, value);
                        if (v) {
                            partial.push(quote(k) + (gap ? ': ' : ':') + v);
                        }
                    }
                }
            } else {

// Otherwise, iterate through all of the keys in the object.

                for (k in value) {
                    if (Object.prototype.hasOwnProperty.call(value, k)) {
                        v = str(k, value);
                        if (v) {
                            partial.push(quote(k) + (gap ? ': ' : ':') + v);
                        }
                    }
                }
            }

// Join all of the member texts together, separated with commas,
// and wrap them in braces.

            v = partial.length === 0
                ? '{}'
                : gap
                ? '{\n' + gap + partial.join(',\n' + gap) + '\n' + mind + '}'
                : '{' + partial.join(',') + '}';
            gap = mind;
            return v;
        }
    }

// If the JSON object does not yet have a stringify method, give it one.

    if (typeof JSON.stringify !== 'function') {
        JSON.stringify = function (value, replacer, space) {

// The stringify method takes a value and an optional replacer, and an optional
// space parameter, and returns a JSON text. The replacer can be a function
// that can replace values, or an array of strings that will select the keys.
// A default replacer method can be provided. Use of the space parameter can
// produce text that is more easily readable.

            var i;
            gap = '';
            indent = '';

// If the space parameter is a number, make an indent string containing that
// many spaces.

            if (typeof space === 'number') {
                for (i = 0; i < space; i += 1) {
                    indent += ' ';
                }

// If the space parameter is a string, it will be used as the indent string.

            } else if (typeof space === 'string') {
                indent = space;
            }

// If there is a replacer, it must be a function or an array.
// Otherwise, throw an error.

            rep = replacer;
            if (replacer && typeof replacer !== 'function' &&
                    (typeof replacer !== 'object' ||
                    typeof replacer.length !== 'number')) {
                throw new Error('JSON.stringify');
            }

// Make a fake root object containing our value under the key of ''.
// Return the result of stringifying the value.

            return str('', {'': value});
        };
    }


// If the JSON object does not yet have a parse method, give it one.

    if (typeof JSON.parse !== 'function') {
        JSON.parse = function (text, reviver) {

// The parse method takes a text and an optional reviver function, and returns
// a JavaScript value if the text is a valid JSON text.

            var j;

            function walk(holder, key) {

// The walk method is used to recursively walk the resulting structure so
// that modifications can be made.

                var k, v, value = holder[key];
                if (value && typeof value === 'object') {
                    for (k in value) {
                        if (Object.prototype.hasOwnProperty.call(value, k)) {
                            v = walk(value, k);
                            if (v !== undefined) {
                                value[k] = v;
                            } else {
                                delete value[k];
                            }
                        }
                    }
                }
                return reviver.call(holder, key, value);
            }


// Parsing happens in four stages. In the first stage, we replace certain
// Unicode characters with escape sequences. JavaScript handles many characters
// incorrectly, either silently deleting them, or treating them as line endings.

            text = String(text);
            cx.lastIndex = 0;
            if (cx.test(text)) {
                text = text.replace(cx, function (a) {
                    return '\\u' +
                        ('0000' + a.charCodeAt(0).toString(16)).slice(-4);
                });
            }

// In the second stage, we run the text against regular expressions that look
// for non-JSON patterns. We are especially concerned with '()' and 'new'
// because they can cause invocation, and '=' because it can cause mutation.
// But just to be safe, we want to reject all unexpected forms.

// We split the second stage into 4 regexp operations in order to work around
// crippling inefficiencies in IE's and Safari's regexp engines. First we
// replace the JSON backslash pairs with '@' (a non-JSON character). Second, we
// replace all simple value tokens with ']' characters. Third, we delete all
// open brackets that follow a colon or comma or that begin the text. Finally,
// we look to see that the remaining characters are only whitespace or ']' or
// ',' or ':' or '{' or '}'. If that is so, then the text is safe for eval.

            if (/^[\],:{}\s]*$/
                    .test(text.replace(/\\(?:["\\\/bfnrt]|u[0-9a-fA-F]{4})/g, '@')
                        .replace(/"[^"\\\n\r]*"|true|false|null|-?\d+(?:\.\d*)?(?:[eE][+\-]?\d+)?/g, ']')
                        .replace(/(?:^|:|,)(?:\s*\[)+/g, ''))) {

// In the third stage we use the eval function to compile the text into a
// JavaScript structure. The '{' operator is subject to a syntactic ambiguity
// in JavaScript: it can begin a block or an object literal. We wrap the text
// in parens to eliminate the ambiguity.

                j = eval('(' + text + ')');

// In the optional fourth stage, we recursively walk the new structure, passing
// each name/value pair to a reviver function for possible transformation.

                return typeof reviver === 'function'
                    ? walk({'': j}, '')
                    : j;
            }

// If the text is not JSON parseable, then a SyntaxError is thrown.

            throw new SyntaxError('JSON.parse');
        };
    }
}());
/*
 * This combined file was created by the DataTables downloader builder:
 *   https://datatables.net/download
 *
 * To rebuild or modify this file with the latest versions of the included
 * software please visit:
 *   https://datatables.net/download/#dt/dt-1.10.18/cr-1.5.0/fc-3.2.5/fh-3.1.4/kt-2.5.0/r-2.2.2/rr-1.2.4
 *
 * Included libraries:
 *   DataTables 1.10.18, ColReorder 1.5.0, FixedColumns 3.2.5, FixedHeader 3.1.4, KeyTable 2.5.0, Responsive 2.2.2, RowReorder 1.2.4
 */

/*!
 DataTables 1.10.18
 ©2008-2018 SpryMedia Ltd - datatables.net/license
*/
(function(h){"function"===typeof define&&define.amd?define(["jquery"],function(E){return h(E,window,document)}):"object"===typeof exports?module.exports=function(E,H){E||(E=window);H||(H="undefined"!==typeof window?require("jquery"):require("jquery")(E));return h(H,E,E.document)}:h(jQuery,window,document)})(function(h,E,H,k){function Z(a){var b,c,d={};h.each(a,function(e){if((b=e.match(/^([^A-Z]+?)([A-Z])/))&&-1!=="a aa ai ao as b fn i m o s ".indexOf(b[1]+" "))c=e.replace(b[0],b[2].toLowerCase()),
d[c]=e,"o"===b[1]&&Z(a[e])});a._hungarianMap=d}function J(a,b,c){a._hungarianMap||Z(a);var d;h.each(b,function(e){d=a._hungarianMap[e];if(d!==k&&(c||b[d]===k))"o"===d.charAt(0)?(b[d]||(b[d]={}),h.extend(!0,b[d],b[e]),J(a[d],b[d],c)):b[d]=b[e]})}function Ca(a){var b=n.defaults.oLanguage,c=b.sDecimal;c&&Da(c);if(a){var d=a.sZeroRecords;!a.sEmptyTable&&(d&&"No data available in table"===b.sEmptyTable)&&F(a,a,"sZeroRecords","sEmptyTable");!a.sLoadingRecords&&(d&&"Loading..."===b.sLoadingRecords)&&F(a,
a,"sZeroRecords","sLoadingRecords");a.sInfoThousands&&(a.sThousands=a.sInfoThousands);(a=a.sDecimal)&&c!==a&&Da(a)}}function eb(a){A(a,"ordering","bSort");A(a,"orderMulti","bSortMulti");A(a,"orderClasses","bSortClasses");A(a,"orderCellsTop","bSortCellsTop");A(a,"order","aaSorting");A(a,"orderFixed","aaSortingFixed");A(a,"paging","bPaginate");A(a,"pagingType","sPaginationType");A(a,"pageLength","iDisplayLength");A(a,"searching","bFilter");"boolean"===typeof a.sScrollX&&(a.sScrollX=a.sScrollX?"100%":
"");"boolean"===typeof a.scrollX&&(a.scrollX=a.scrollX?"100%":"");if(a=a.aoSearchCols)for(var b=0,c=a.length;b<c;b++)a[b]&&J(n.models.oSearch,a[b])}function fb(a){A(a,"orderable","bSortable");A(a,"orderData","aDataSort");A(a,"orderSequence","asSorting");A(a,"orderDataType","sortDataType");var b=a.aDataSort;"number"===typeof b&&!h.isArray(b)&&(a.aDataSort=[b])}function gb(a){if(!n.__browser){var b={};n.__browser=b;var c=h("<div/>").css({position:"fixed",top:0,left:-1*h(E).scrollLeft(),height:1,width:1,
overflow:"hidden"}).append(h("<div/>").css({position:"absolute",top:1,left:1,width:100,overflow:"scroll"}).append(h("<div/>").css({width:"100%",height:10}))).appendTo("body"),d=c.children(),e=d.children();b.barWidth=d[0].offsetWidth-d[0].clientWidth;b.bScrollOversize=100===e[0].offsetWidth&&100!==d[0].clientWidth;b.bScrollbarLeft=1!==Math.round(e.offset().left);b.bBounding=c[0].getBoundingClientRect().width?!0:!1;c.remove()}h.extend(a.oBrowser,n.__browser);a.oScroll.iBarWidth=n.__browser.barWidth}
function hb(a,b,c,d,e,f){var g,j=!1;c!==k&&(g=c,j=!0);for(;d!==e;)a.hasOwnProperty(d)&&(g=j?b(g,a[d],d,a):a[d],j=!0,d+=f);return g}function Ea(a,b){var c=n.defaults.column,d=a.aoColumns.length,c=h.extend({},n.models.oColumn,c,{nTh:b?b:H.createElement("th"),sTitle:c.sTitle?c.sTitle:b?b.innerHTML:"",aDataSort:c.aDataSort?c.aDataSort:[d],mData:c.mData?c.mData:d,idx:d});a.aoColumns.push(c);c=a.aoPreSearchCols;c[d]=h.extend({},n.models.oSearch,c[d]);ka(a,d,h(b).data())}function ka(a,b,c){var b=a.aoColumns[b],
d=a.oClasses,e=h(b.nTh);if(!b.sWidthOrig){b.sWidthOrig=e.attr("width")||null;var f=(e.attr("style")||"").match(/width:\s*(\d+[pxem%]+)/);f&&(b.sWidthOrig=f[1])}c!==k&&null!==c&&(fb(c),J(n.defaults.column,c),c.mDataProp!==k&&!c.mData&&(c.mData=c.mDataProp),c.sType&&(b._sManualType=c.sType),c.className&&!c.sClass&&(c.sClass=c.className),c.sClass&&e.addClass(c.sClass),h.extend(b,c),F(b,c,"sWidth","sWidthOrig"),c.iDataSort!==k&&(b.aDataSort=[c.iDataSort]),F(b,c,"aDataSort"));var g=b.mData,j=S(g),i=b.mRender?
S(b.mRender):null,c=function(a){return"string"===typeof a&&-1!==a.indexOf("@")};b._bAttrSrc=h.isPlainObject(g)&&(c(g.sort)||c(g.type)||c(g.filter));b._setter=null;b.fnGetData=function(a,b,c){var d=j(a,b,k,c);return i&&b?i(d,b,a,c):d};b.fnSetData=function(a,b,c){return N(g)(a,b,c)};"number"!==typeof g&&(a._rowReadObject=!0);a.oFeatures.bSort||(b.bSortable=!1,e.addClass(d.sSortableNone));a=-1!==h.inArray("asc",b.asSorting);c=-1!==h.inArray("desc",b.asSorting);!b.bSortable||!a&&!c?(b.sSortingClass=d.sSortableNone,
b.sSortingClassJUI=""):a&&!c?(b.sSortingClass=d.sSortableAsc,b.sSortingClassJUI=d.sSortJUIAscAllowed):!a&&c?(b.sSortingClass=d.sSortableDesc,b.sSortingClassJUI=d.sSortJUIDescAllowed):(b.sSortingClass=d.sSortable,b.sSortingClassJUI=d.sSortJUI)}function $(a){if(!1!==a.oFeatures.bAutoWidth){var b=a.aoColumns;Fa(a);for(var c=0,d=b.length;c<d;c++)b[c].nTh.style.width=b[c].sWidth}b=a.oScroll;(""!==b.sY||""!==b.sX)&&la(a);r(a,null,"column-sizing",[a])}function aa(a,b){var c=ma(a,"bVisible");return"number"===
typeof c[b]?c[b]:null}function ba(a,b){var c=ma(a,"bVisible"),c=h.inArray(b,c);return-1!==c?c:null}function V(a){var b=0;h.each(a.aoColumns,function(a,d){d.bVisible&&"none"!==h(d.nTh).css("display")&&b++});return b}function ma(a,b){var c=[];h.map(a.aoColumns,function(a,e){a[b]&&c.push(e)});return c}function Ga(a){var b=a.aoColumns,c=a.aoData,d=n.ext.type.detect,e,f,g,j,i,h,l,q,t;e=0;for(f=b.length;e<f;e++)if(l=b[e],t=[],!l.sType&&l._sManualType)l.sType=l._sManualType;else if(!l.sType){g=0;for(j=d.length;g<
j;g++){i=0;for(h=c.length;i<h;i++){t[i]===k&&(t[i]=B(a,i,e,"type"));q=d[g](t[i],a);if(!q&&g!==d.length-1)break;if("html"===q)break}if(q){l.sType=q;break}}l.sType||(l.sType="string")}}function ib(a,b,c,d){var e,f,g,j,i,m,l=a.aoColumns;if(b)for(e=b.length-1;0<=e;e--){m=b[e];var q=m.targets!==k?m.targets:m.aTargets;h.isArray(q)||(q=[q]);f=0;for(g=q.length;f<g;f++)if("number"===typeof q[f]&&0<=q[f]){for(;l.length<=q[f];)Ea(a);d(q[f],m)}else if("number"===typeof q[f]&&0>q[f])d(l.length+q[f],m);else if("string"===
typeof q[f]){j=0;for(i=l.length;j<i;j++)("_all"==q[f]||h(l[j].nTh).hasClass(q[f]))&&d(j,m)}}if(c){e=0;for(a=c.length;e<a;e++)d(e,c[e])}}function O(a,b,c,d){var e=a.aoData.length,f=h.extend(!0,{},n.models.oRow,{src:c?"dom":"data",idx:e});f._aData=b;a.aoData.push(f);for(var g=a.aoColumns,j=0,i=g.length;j<i;j++)g[j].sType=null;a.aiDisplayMaster.push(e);b=a.rowIdFn(b);b!==k&&(a.aIds[b]=f);(c||!a.oFeatures.bDeferRender)&&Ha(a,e,c,d);return e}function na(a,b){var c;b instanceof h||(b=h(b));return b.map(function(b,
e){c=Ia(a,e);return O(a,c.data,e,c.cells)})}function B(a,b,c,d){var e=a.iDraw,f=a.aoColumns[c],g=a.aoData[b]._aData,j=f.sDefaultContent,i=f.fnGetData(g,d,{settings:a,row:b,col:c});if(i===k)return a.iDrawError!=e&&null===j&&(K(a,0,"Requested unknown parameter "+("function"==typeof f.mData?"{function}":"'"+f.mData+"'")+" for row "+b+", column "+c,4),a.iDrawError=e),j;if((i===g||null===i)&&null!==j&&d!==k)i=j;else if("function"===typeof i)return i.call(g);return null===i&&"display"==d?"":i}function jb(a,
b,c,d){a.aoColumns[c].fnSetData(a.aoData[b]._aData,d,{settings:a,row:b,col:c})}function Ja(a){return h.map(a.match(/(\\.|[^\.])+/g)||[""],function(a){return a.replace(/\\\./g,".")})}function S(a){if(h.isPlainObject(a)){var b={};h.each(a,function(a,c){c&&(b[a]=S(c))});return function(a,c,f,g){var j=b[c]||b._;return j!==k?j(a,c,f,g):a}}if(null===a)return function(a){return a};if("function"===typeof a)return function(b,c,f,g){return a(b,c,f,g)};if("string"===typeof a&&(-1!==a.indexOf(".")||-1!==a.indexOf("[")||
-1!==a.indexOf("("))){var c=function(a,b,f){var g,j;if(""!==f){j=Ja(f);for(var i=0,m=j.length;i<m;i++){f=j[i].match(ca);g=j[i].match(W);if(f){j[i]=j[i].replace(ca,"");""!==j[i]&&(a=a[j[i]]);g=[];j.splice(0,i+1);j=j.join(".");if(h.isArray(a)){i=0;for(m=a.length;i<m;i++)g.push(c(a[i],b,j))}a=f[0].substring(1,f[0].length-1);a=""===a?g:g.join(a);break}else if(g){j[i]=j[i].replace(W,"");a=a[j[i]]();continue}if(null===a||a[j[i]]===k)return k;a=a[j[i]]}}return a};return function(b,e){return c(b,e,a)}}return function(b){return b[a]}}
function N(a){if(h.isPlainObject(a))return N(a._);if(null===a)return function(){};if("function"===typeof a)return function(b,d,e){a(b,"set",d,e)};if("string"===typeof a&&(-1!==a.indexOf(".")||-1!==a.indexOf("[")||-1!==a.indexOf("("))){var b=function(a,d,e){var e=Ja(e),f;f=e[e.length-1];for(var g,j,i=0,m=e.length-1;i<m;i++){g=e[i].match(ca);j=e[i].match(W);if(g){e[i]=e[i].replace(ca,"");a[e[i]]=[];f=e.slice();f.splice(0,i+1);g=f.join(".");if(h.isArray(d)){j=0;for(m=d.length;j<m;j++)f={},b(f,d[j],g),
a[e[i]].push(f)}else a[e[i]]=d;return}j&&(e[i]=e[i].replace(W,""),a=a[e[i]](d));if(null===a[e[i]]||a[e[i]]===k)a[e[i]]={};a=a[e[i]]}if(f.match(W))a[f.replace(W,"")](d);else a[f.replace(ca,"")]=d};return function(c,d){return b(c,d,a)}}return function(b,d){b[a]=d}}function Ka(a){return D(a.aoData,"_aData")}function oa(a){a.aoData.length=0;a.aiDisplayMaster.length=0;a.aiDisplay.length=0;a.aIds={}}function pa(a,b,c){for(var d=-1,e=0,f=a.length;e<f;e++)a[e]==b?d=e:a[e]>b&&a[e]--; -1!=d&&c===k&&a.splice(d,
1)}function da(a,b,c,d){var e=a.aoData[b],f,g=function(c,d){for(;c.childNodes.length;)c.removeChild(c.firstChild);c.innerHTML=B(a,b,d,"display")};if("dom"===c||(!c||"auto"===c)&&"dom"===e.src)e._aData=Ia(a,e,d,d===k?k:e._aData).data;else{var j=e.anCells;if(j)if(d!==k)g(j[d],d);else{c=0;for(f=j.length;c<f;c++)g(j[c],c)}}e._aSortData=null;e._aFilterData=null;g=a.aoColumns;if(d!==k)g[d].sType=null;else{c=0;for(f=g.length;c<f;c++)g[c].sType=null;La(a,e)}}function Ia(a,b,c,d){var e=[],f=b.firstChild,g,
j,i=0,m,l=a.aoColumns,q=a._rowReadObject,d=d!==k?d:q?{}:[],t=function(a,b){if("string"===typeof a){var c=a.indexOf("@");-1!==c&&(c=a.substring(c+1),N(a)(d,b.getAttribute(c)))}},G=function(a){if(c===k||c===i)j=l[i],m=h.trim(a.innerHTML),j&&j._bAttrSrc?(N(j.mData._)(d,m),t(j.mData.sort,a),t(j.mData.type,a),t(j.mData.filter,a)):q?(j._setter||(j._setter=N(j.mData)),j._setter(d,m)):d[i]=m;i++};if(f)for(;f;){g=f.nodeName.toUpperCase();if("TD"==g||"TH"==g)G(f),e.push(f);f=f.nextSibling}else{e=b.anCells;
f=0;for(g=e.length;f<g;f++)G(e[f])}if(b=b.firstChild?b:b.nTr)(b=b.getAttribute("id"))&&N(a.rowId)(d,b);return{data:d,cells:e}}function Ha(a,b,c,d){var e=a.aoData[b],f=e._aData,g=[],j,i,m,l,q;if(null===e.nTr){j=c||H.createElement("tr");e.nTr=j;e.anCells=g;j._DT_RowIndex=b;La(a,e);l=0;for(q=a.aoColumns.length;l<q;l++){m=a.aoColumns[l];i=c?d[l]:H.createElement(m.sCellType);i._DT_CellIndex={row:b,column:l};g.push(i);if((!c||m.mRender||m.mData!==l)&&(!h.isPlainObject(m.mData)||m.mData._!==l+".display"))i.innerHTML=
B(a,b,l,"display");m.sClass&&(i.className+=" "+m.sClass);m.bVisible&&!c?j.appendChild(i):!m.bVisible&&c&&i.parentNode.removeChild(i);m.fnCreatedCell&&m.fnCreatedCell.call(a.oInstance,i,B(a,b,l),f,b,l)}r(a,"aoRowCreatedCallback",null,[j,f,b,g])}e.nTr.setAttribute("role","row")}function La(a,b){var c=b.nTr,d=b._aData;if(c){var e=a.rowIdFn(d);e&&(c.id=e);d.DT_RowClass&&(e=d.DT_RowClass.split(" "),b.__rowc=b.__rowc?qa(b.__rowc.concat(e)):e,h(c).removeClass(b.__rowc.join(" ")).addClass(d.DT_RowClass));
d.DT_RowAttr&&h(c).attr(d.DT_RowAttr);d.DT_RowData&&h(c).data(d.DT_RowData)}}function kb(a){var b,c,d,e,f,g=a.nTHead,j=a.nTFoot,i=0===h("th, td",g).length,m=a.oClasses,l=a.aoColumns;i&&(e=h("<tr/>").appendTo(g));b=0;for(c=l.length;b<c;b++)f=l[b],d=h(f.nTh).addClass(f.sClass),i&&d.appendTo(e),a.oFeatures.bSort&&(d.addClass(f.sSortingClass),!1!==f.bSortable&&(d.attr("tabindex",a.iTabIndex).attr("aria-controls",a.sTableId),Ma(a,f.nTh,b))),f.sTitle!=d[0].innerHTML&&d.html(f.sTitle),Na(a,"header")(a,d,
f,m);i&&ea(a.aoHeader,g);h(g).find(">tr").attr("role","row");h(g).find(">tr>th, >tr>td").addClass(m.sHeaderTH);h(j).find(">tr>th, >tr>td").addClass(m.sFooterTH);if(null!==j){a=a.aoFooter[0];b=0;for(c=a.length;b<c;b++)f=l[b],f.nTf=a[b].cell,f.sClass&&h(f.nTf).addClass(f.sClass)}}function fa(a,b,c){var d,e,f,g=[],j=[],i=a.aoColumns.length,m;if(b){c===k&&(c=!1);d=0;for(e=b.length;d<e;d++){g[d]=b[d].slice();g[d].nTr=b[d].nTr;for(f=i-1;0<=f;f--)!a.aoColumns[f].bVisible&&!c&&g[d].splice(f,1);j.push([])}d=
0;for(e=g.length;d<e;d++){if(a=g[d].nTr)for(;f=a.firstChild;)a.removeChild(f);f=0;for(b=g[d].length;f<b;f++)if(m=i=1,j[d][f]===k){a.appendChild(g[d][f].cell);for(j[d][f]=1;g[d+i]!==k&&g[d][f].cell==g[d+i][f].cell;)j[d+i][f]=1,i++;for(;g[d][f+m]!==k&&g[d][f].cell==g[d][f+m].cell;){for(c=0;c<i;c++)j[d+c][f+m]=1;m++}h(g[d][f].cell).attr("rowspan",i).attr("colspan",m)}}}}function P(a){var b=r(a,"aoPreDrawCallback","preDraw",[a]);if(-1!==h.inArray(!1,b))C(a,!1);else{var b=[],c=0,d=a.asStripeClasses,e=
d.length,f=a.oLanguage,g=a.iInitDisplayStart,j="ssp"==y(a),i=a.aiDisplay;a.bDrawing=!0;g!==k&&-1!==g&&(a._iDisplayStart=j?g:g>=a.fnRecordsDisplay()?0:g,a.iInitDisplayStart=-1);var g=a._iDisplayStart,m=a.fnDisplayEnd();if(a.bDeferLoading)a.bDeferLoading=!1,a.iDraw++,C(a,!1);else if(j){if(!a.bDestroying&&!lb(a))return}else a.iDraw++;if(0!==i.length){f=j?a.aoData.length:m;for(j=j?0:g;j<f;j++){var l=i[j],q=a.aoData[l];null===q.nTr&&Ha(a,l);var t=q.nTr;if(0!==e){var G=d[c%e];q._sRowStripe!=G&&(h(t).removeClass(q._sRowStripe).addClass(G),
q._sRowStripe=G)}r(a,"aoRowCallback",null,[t,q._aData,c,j,l]);b.push(t);c++}}else c=f.sZeroRecords,1==a.iDraw&&"ajax"==y(a)?c=f.sLoadingRecords:f.sEmptyTable&&0===a.fnRecordsTotal()&&(c=f.sEmptyTable),b[0]=h("<tr/>",{"class":e?d[0]:""}).append(h("<td />",{valign:"top",colSpan:V(a),"class":a.oClasses.sRowEmpty}).html(c))[0];r(a,"aoHeaderCallback","header",[h(a.nTHead).children("tr")[0],Ka(a),g,m,i]);r(a,"aoFooterCallback","footer",[h(a.nTFoot).children("tr")[0],Ka(a),g,m,i]);d=h(a.nTBody);d.children().detach();
d.append(h(b));r(a,"aoDrawCallback","draw",[a]);a.bSorted=!1;a.bFiltered=!1;a.bDrawing=!1}}function T(a,b){var c=a.oFeatures,d=c.bFilter;c.bSort&&mb(a);d?ga(a,a.oPreviousSearch):a.aiDisplay=a.aiDisplayMaster.slice();!0!==b&&(a._iDisplayStart=0);a._drawHold=b;P(a);a._drawHold=!1}function nb(a){var b=a.oClasses,c=h(a.nTable),c=h("<div/>").insertBefore(c),d=a.oFeatures,e=h("<div/>",{id:a.sTableId+"_wrapper","class":b.sWrapper+(a.nTFoot?"":" "+b.sNoFooter)});a.nHolding=c[0];a.nTableWrapper=e[0];a.nTableReinsertBefore=
a.nTable.nextSibling;for(var f=a.sDom.split(""),g,j,i,m,l,q,k=0;k<f.length;k++){g=null;j=f[k];if("<"==j){i=h("<div/>")[0];m=f[k+1];if("'"==m||'"'==m){l="";for(q=2;f[k+q]!=m;)l+=f[k+q],q++;"H"==l?l=b.sJUIHeader:"F"==l&&(l=b.sJUIFooter);-1!=l.indexOf(".")?(m=l.split("."),i.id=m[0].substr(1,m[0].length-1),i.className=m[1]):"#"==l.charAt(0)?i.id=l.substr(1,l.length-1):i.className=l;k+=q}e.append(i);e=h(i)}else if(">"==j)e=e.parent();else if("l"==j&&d.bPaginate&&d.bLengthChange)g=ob(a);else if("f"==j&&
d.bFilter)g=pb(a);else if("r"==j&&d.bProcessing)g=qb(a);else if("t"==j)g=rb(a);else if("i"==j&&d.bInfo)g=sb(a);else if("p"==j&&d.bPaginate)g=tb(a);else if(0!==n.ext.feature.length){i=n.ext.feature;q=0;for(m=i.length;q<m;q++)if(j==i[q].cFeature){g=i[q].fnInit(a);break}}g&&(i=a.aanFeatures,i[j]||(i[j]=[]),i[j].push(g),e.append(g))}c.replaceWith(e);a.nHolding=null}function ea(a,b){var c=h(b).children("tr"),d,e,f,g,j,i,m,l,q,k;a.splice(0,a.length);f=0;for(i=c.length;f<i;f++)a.push([]);f=0;for(i=c.length;f<
i;f++){d=c[f];for(e=d.firstChild;e;){if("TD"==e.nodeName.toUpperCase()||"TH"==e.nodeName.toUpperCase()){l=1*e.getAttribute("colspan");q=1*e.getAttribute("rowspan");l=!l||0===l||1===l?1:l;q=!q||0===q||1===q?1:q;g=0;for(j=a[f];j[g];)g++;m=g;k=1===l?!0:!1;for(j=0;j<l;j++)for(g=0;g<q;g++)a[f+g][m+j]={cell:e,unique:k},a[f+g].nTr=d}e=e.nextSibling}}}function ra(a,b,c){var d=[];c||(c=a.aoHeader,b&&(c=[],ea(c,b)));for(var b=0,e=c.length;b<e;b++)for(var f=0,g=c[b].length;f<g;f++)if(c[b][f].unique&&(!d[f]||
!a.bSortCellsTop))d[f]=c[b][f].cell;return d}function sa(a,b,c){r(a,"aoServerParams","serverParams",[b]);if(b&&h.isArray(b)){var d={},e=/(.*?)\[\]$/;h.each(b,function(a,b){var c=b.name.match(e);c?(c=c[0],d[c]||(d[c]=[]),d[c].push(b.value)):d[b.name]=b.value});b=d}var f,g=a.ajax,j=a.oInstance,i=function(b){r(a,null,"xhr",[a,b,a.jqXHR]);c(b)};if(h.isPlainObject(g)&&g.data){f=g.data;var m="function"===typeof f?f(b,a):f,b="function"===typeof f&&m?m:h.extend(!0,b,m);delete g.data}m={data:b,success:function(b){var c=
b.error||b.sError;c&&K(a,0,c);a.json=b;i(b)},dataType:"json",cache:!1,type:a.sServerMethod,error:function(b,c){var d=r(a,null,"xhr",[a,null,a.jqXHR]);-1===h.inArray(!0,d)&&("parsererror"==c?K(a,0,"Invalid JSON response",1):4===b.readyState&&K(a,0,"Ajax error",7));C(a,!1)}};a.oAjaxData=b;r(a,null,"preXhr",[a,b]);a.fnServerData?a.fnServerData.call(j,a.sAjaxSource,h.map(b,function(a,b){return{name:b,value:a}}),i,a):a.sAjaxSource||"string"===typeof g?a.jqXHR=h.ajax(h.extend(m,{url:g||a.sAjaxSource})):
"function"===typeof g?a.jqXHR=g.call(j,b,i,a):(a.jqXHR=h.ajax(h.extend(m,g)),g.data=f)}function lb(a){return a.bAjaxDataGet?(a.iDraw++,C(a,!0),sa(a,ub(a),function(b){vb(a,b)}),!1):!0}function ub(a){var b=a.aoColumns,c=b.length,d=a.oFeatures,e=a.oPreviousSearch,f=a.aoPreSearchCols,g,j=[],i,m,l,k=X(a);g=a._iDisplayStart;i=!1!==d.bPaginate?a._iDisplayLength:-1;var t=function(a,b){j.push({name:a,value:b})};t("sEcho",a.iDraw);t("iColumns",c);t("sColumns",D(b,"sName").join(","));t("iDisplayStart",g);t("iDisplayLength",
i);var G={draw:a.iDraw,columns:[],order:[],start:g,length:i,search:{value:e.sSearch,regex:e.bRegex}};for(g=0;g<c;g++)m=b[g],l=f[g],i="function"==typeof m.mData?"function":m.mData,G.columns.push({data:i,name:m.sName,searchable:m.bSearchable,orderable:m.bSortable,search:{value:l.sSearch,regex:l.bRegex}}),t("mDataProp_"+g,i),d.bFilter&&(t("sSearch_"+g,l.sSearch),t("bRegex_"+g,l.bRegex),t("bSearchable_"+g,m.bSearchable)),d.bSort&&t("bSortable_"+g,m.bSortable);d.bFilter&&(t("sSearch",e.sSearch),t("bRegex",
e.bRegex));d.bSort&&(h.each(k,function(a,b){G.order.push({column:b.col,dir:b.dir});t("iSortCol_"+a,b.col);t("sSortDir_"+a,b.dir)}),t("iSortingCols",k.length));b=n.ext.legacy.ajax;return null===b?a.sAjaxSource?j:G:b?j:G}function vb(a,b){var c=ta(a,b),d=b.sEcho!==k?b.sEcho:b.draw,e=b.iTotalRecords!==k?b.iTotalRecords:b.recordsTotal,f=b.iTotalDisplayRecords!==k?b.iTotalDisplayRecords:b.recordsFiltered;if(d){if(1*d<a.iDraw)return;a.iDraw=1*d}oa(a);a._iRecordsTotal=parseInt(e,10);a._iRecordsDisplay=parseInt(f,
10);d=0;for(e=c.length;d<e;d++)O(a,c[d]);a.aiDisplay=a.aiDisplayMaster.slice();a.bAjaxDataGet=!1;P(a);a._bInitComplete||ua(a,b);a.bAjaxDataGet=!0;C(a,!1)}function ta(a,b){var c=h.isPlainObject(a.ajax)&&a.ajax.dataSrc!==k?a.ajax.dataSrc:a.sAjaxDataProp;return"data"===c?b.aaData||b[c]:""!==c?S(c)(b):b}function pb(a){var b=a.oClasses,c=a.sTableId,d=a.oLanguage,e=a.oPreviousSearch,f=a.aanFeatures,g='<input type="search" class="'+b.sFilterInput+'"/>',j=d.sSearch,j=j.match(/_INPUT_/)?j.replace("_INPUT_",
g):j+g,b=h("<div/>",{id:!f.f?c+"_filter":null,"class":b.sFilter}).append(h("<label/>").append(j)),f=function(){var b=!this.value?"":this.value;b!=e.sSearch&&(ga(a,{sSearch:b,bRegex:e.bRegex,bSmart:e.bSmart,bCaseInsensitive:e.bCaseInsensitive}),a._iDisplayStart=0,P(a))},g=null!==a.searchDelay?a.searchDelay:"ssp"===y(a)?400:0,i=h("input",b).val(e.sSearch).attr("placeholder",d.sSearchPlaceholder).on("keyup.DT search.DT input.DT paste.DT cut.DT",g?Oa(f,g):f).on("keypress.DT",function(a){if(13==a.keyCode)return!1}).attr("aria-controls",
c);h(a.nTable).on("search.dt.DT",function(b,c){if(a===c)try{i[0]!==H.activeElement&&i.val(e.sSearch)}catch(d){}});return b[0]}function ga(a,b,c){var d=a.oPreviousSearch,e=a.aoPreSearchCols,f=function(a){d.sSearch=a.sSearch;d.bRegex=a.bRegex;d.bSmart=a.bSmart;d.bCaseInsensitive=a.bCaseInsensitive};Ga(a);if("ssp"!=y(a)){wb(a,b.sSearch,c,b.bEscapeRegex!==k?!b.bEscapeRegex:b.bRegex,b.bSmart,b.bCaseInsensitive);f(b);for(b=0;b<e.length;b++)xb(a,e[b].sSearch,b,e[b].bEscapeRegex!==k?!e[b].bEscapeRegex:e[b].bRegex,
e[b].bSmart,e[b].bCaseInsensitive);yb(a)}else f(b);a.bFiltered=!0;r(a,null,"search",[a])}function yb(a){for(var b=n.ext.search,c=a.aiDisplay,d,e,f=0,g=b.length;f<g;f++){for(var j=[],i=0,m=c.length;i<m;i++)e=c[i],d=a.aoData[e],b[f](a,d._aFilterData,e,d._aData,i)&&j.push(e);c.length=0;h.merge(c,j)}}function xb(a,b,c,d,e,f){if(""!==b){for(var g=[],j=a.aiDisplay,d=Pa(b,d,e,f),e=0;e<j.length;e++)b=a.aoData[j[e]]._aFilterData[c],d.test(b)&&g.push(j[e]);a.aiDisplay=g}}function wb(a,b,c,d,e,f){var d=Pa(b,
d,e,f),f=a.oPreviousSearch.sSearch,g=a.aiDisplayMaster,j,e=[];0!==n.ext.search.length&&(c=!0);j=zb(a);if(0>=b.length)a.aiDisplay=g.slice();else{if(j||c||f.length>b.length||0!==b.indexOf(f)||a.bSorted)a.aiDisplay=g.slice();b=a.aiDisplay;for(c=0;c<b.length;c++)d.test(a.aoData[b[c]]._sFilterRow)&&e.push(b[c]);a.aiDisplay=e}}function Pa(a,b,c,d){a=b?a:Qa(a);c&&(a="^(?=.*?"+h.map(a.match(/"[^"]+"|[^ ]+/g)||[""],function(a){if('"'===a.charAt(0))var b=a.match(/^"(.*)"$/),a=b?b[1]:a;return a.replace('"',
"")}).join(")(?=.*?")+").*$");return RegExp(a,d?"i":"")}function zb(a){var b=a.aoColumns,c,d,e,f,g,j,i,h,l=n.ext.type.search;c=!1;d=0;for(f=a.aoData.length;d<f;d++)if(h=a.aoData[d],!h._aFilterData){j=[];e=0;for(g=b.length;e<g;e++)c=b[e],c.bSearchable?(i=B(a,d,e,"filter"),l[c.sType]&&(i=l[c.sType](i)),null===i&&(i=""),"string"!==typeof i&&i.toString&&(i=i.toString())):i="",i.indexOf&&-1!==i.indexOf("&")&&(va.innerHTML=i,i=Wb?va.textContent:va.innerText),i.replace&&(i=i.replace(/[\r\n]/g,"")),j.push(i);
h._aFilterData=j;h._sFilterRow=j.join("  ");c=!0}return c}function Ab(a){return{search:a.sSearch,smart:a.bSmart,regex:a.bRegex,caseInsensitive:a.bCaseInsensitive}}function Bb(a){return{sSearch:a.search,bSmart:a.smart,bRegex:a.regex,bCaseInsensitive:a.caseInsensitive}}function sb(a){var b=a.sTableId,c=a.aanFeatures.i,d=h("<div/>",{"class":a.oClasses.sInfo,id:!c?b+"_info":null});c||(a.aoDrawCallback.push({fn:Cb,sName:"information"}),d.attr("role","status").attr("aria-live","polite"),h(a.nTable).attr("aria-describedby",
b+"_info"));return d[0]}function Cb(a){var b=a.aanFeatures.i;if(0!==b.length){var c=a.oLanguage,d=a._iDisplayStart+1,e=a.fnDisplayEnd(),f=a.fnRecordsTotal(),g=a.fnRecordsDisplay(),j=g?c.sInfo:c.sInfoEmpty;g!==f&&(j+=" "+c.sInfoFiltered);j+=c.sInfoPostFix;j=Db(a,j);c=c.fnInfoCallback;null!==c&&(j=c.call(a.oInstance,a,d,e,f,g,j));h(b).html(j)}}function Db(a,b){var c=a.fnFormatNumber,d=a._iDisplayStart+1,e=a._iDisplayLength,f=a.fnRecordsDisplay(),g=-1===e;return b.replace(/_START_/g,c.call(a,d)).replace(/_END_/g,
c.call(a,a.fnDisplayEnd())).replace(/_MAX_/g,c.call(a,a.fnRecordsTotal())).replace(/_TOTAL_/g,c.call(a,f)).replace(/_PAGE_/g,c.call(a,g?1:Math.ceil(d/e))).replace(/_PAGES_/g,c.call(a,g?1:Math.ceil(f/e)))}function ha(a){var b,c,d=a.iInitDisplayStart,e=a.aoColumns,f;c=a.oFeatures;var g=a.bDeferLoading;if(a.bInitialised){nb(a);kb(a);fa(a,a.aoHeader);fa(a,a.aoFooter);C(a,!0);c.bAutoWidth&&Fa(a);b=0;for(c=e.length;b<c;b++)f=e[b],f.sWidth&&(f.nTh.style.width=v(f.sWidth));r(a,null,"preInit",[a]);T(a);e=
y(a);if("ssp"!=e||g)"ajax"==e?sa(a,[],function(c){var f=ta(a,c);for(b=0;b<f.length;b++)O(a,f[b]);a.iInitDisplayStart=d;T(a);C(a,!1);ua(a,c)},a):(C(a,!1),ua(a))}else setTimeout(function(){ha(a)},200)}function ua(a,b){a._bInitComplete=!0;(b||a.oInit.aaData)&&$(a);r(a,null,"plugin-init",[a,b]);r(a,"aoInitComplete","init",[a,b])}function Ra(a,b){var c=parseInt(b,10);a._iDisplayLength=c;Sa(a);r(a,null,"length",[a,c])}function ob(a){for(var b=a.oClasses,c=a.sTableId,d=a.aLengthMenu,e=h.isArray(d[0]),f=
e?d[0]:d,d=e?d[1]:d,e=h("<select/>",{name:c+"_length","aria-controls":c,"class":b.sLengthSelect}),g=0,j=f.length;g<j;g++)e[0][g]=new Option("number"===typeof d[g]?a.fnFormatNumber(d[g]):d[g],f[g]);var i=h("<div><label/></div>").addClass(b.sLength);a.aanFeatures.l||(i[0].id=c+"_length");i.children().append(a.oLanguage.sLengthMenu.replace("_MENU_",e[0].outerHTML));h("select",i).val(a._iDisplayLength).on("change.DT",function(){Ra(a,h(this).val());P(a)});h(a.nTable).on("length.dt.DT",function(b,c,d){a===
c&&h("select",i).val(d)});return i[0]}function tb(a){var b=a.sPaginationType,c=n.ext.pager[b],d="function"===typeof c,e=function(a){P(a)},b=h("<div/>").addClass(a.oClasses.sPaging+b)[0],f=a.aanFeatures;d||c.fnInit(a,b,e);f.p||(b.id=a.sTableId+"_paginate",a.aoDrawCallback.push({fn:function(a){if(d){var b=a._iDisplayStart,i=a._iDisplayLength,h=a.fnRecordsDisplay(),l=-1===i,b=l?0:Math.ceil(b/i),i=l?1:Math.ceil(h/i),h=c(b,i),k,l=0;for(k=f.p.length;l<k;l++)Na(a,"pageButton")(a,f.p[l],l,h,b,i)}else c.fnUpdate(a,
e)},sName:"pagination"}));return b}function Ta(a,b,c){var d=a._iDisplayStart,e=a._iDisplayLength,f=a.fnRecordsDisplay();0===f||-1===e?d=0:"number"===typeof b?(d=b*e,d>f&&(d=0)):"first"==b?d=0:"previous"==b?(d=0<=e?d-e:0,0>d&&(d=0)):"next"==b?d+e<f&&(d+=e):"last"==b?d=Math.floor((f-1)/e)*e:K(a,0,"Unknown paging action: "+b,5);b=a._iDisplayStart!==d;a._iDisplayStart=d;b&&(r(a,null,"page",[a]),c&&P(a));return b}function qb(a){return h("<div/>",{id:!a.aanFeatures.r?a.sTableId+"_processing":null,"class":a.oClasses.sProcessing}).html(a.oLanguage.sProcessing).insertBefore(a.nTable)[0]}
function C(a,b){a.oFeatures.bProcessing&&h(a.aanFeatures.r).css("display",b?"block":"none");r(a,null,"processing",[a,b])}function rb(a){var b=h(a.nTable);b.attr("role","grid");var c=a.oScroll;if(""===c.sX&&""===c.sY)return a.nTable;var d=c.sX,e=c.sY,f=a.oClasses,g=b.children("caption"),j=g.length?g[0]._captionSide:null,i=h(b[0].cloneNode(!1)),m=h(b[0].cloneNode(!1)),l=b.children("tfoot");l.length||(l=null);i=h("<div/>",{"class":f.sScrollWrapper}).append(h("<div/>",{"class":f.sScrollHead}).css({overflow:"hidden",
position:"relative",border:0,width:d?!d?null:v(d):"100%"}).append(h("<div/>",{"class":f.sScrollHeadInner}).css({"box-sizing":"content-box",width:c.sXInner||"100%"}).append(i.removeAttr("id").css("margin-left",0).append("top"===j?g:null).append(b.children("thead"))))).append(h("<div/>",{"class":f.sScrollBody}).css({position:"relative",overflow:"auto",width:!d?null:v(d)}).append(b));l&&i.append(h("<div/>",{"class":f.sScrollFoot}).css({overflow:"hidden",border:0,width:d?!d?null:v(d):"100%"}).append(h("<div/>",
{"class":f.sScrollFootInner}).append(m.removeAttr("id").css("margin-left",0).append("bottom"===j?g:null).append(b.children("tfoot")))));var b=i.children(),k=b[0],f=b[1],t=l?b[2]:null;if(d)h(f).on("scroll.DT",function(){var a=this.scrollLeft;k.scrollLeft=a;l&&(t.scrollLeft=a)});h(f).css(e&&c.bCollapse?"max-height":"height",e);a.nScrollHead=k;a.nScrollBody=f;a.nScrollFoot=t;a.aoDrawCallback.push({fn:la,sName:"scrolling"});return i[0]}function la(a){var b=a.oScroll,c=b.sX,d=b.sXInner,e=b.sY,b=b.iBarWidth,
f=h(a.nScrollHead),g=f[0].style,j=f.children("div"),i=j[0].style,m=j.children("table"),j=a.nScrollBody,l=h(j),q=j.style,t=h(a.nScrollFoot).children("div"),n=t.children("table"),o=h(a.nTHead),p=h(a.nTable),s=p[0],r=s.style,u=a.nTFoot?h(a.nTFoot):null,x=a.oBrowser,U=x.bScrollOversize,Xb=D(a.aoColumns,"nTh"),Q,L,R,w,Ua=[],y=[],z=[],A=[],B,C=function(a){a=a.style;a.paddingTop="0";a.paddingBottom="0";a.borderTopWidth="0";a.borderBottomWidth="0";a.height=0};L=j.scrollHeight>j.clientHeight;if(a.scrollBarVis!==
L&&a.scrollBarVis!==k)a.scrollBarVis=L,$(a);else{a.scrollBarVis=L;p.children("thead, tfoot").remove();u&&(R=u.clone().prependTo(p),Q=u.find("tr"),R=R.find("tr"));w=o.clone().prependTo(p);o=o.find("tr");L=w.find("tr");w.find("th, td").removeAttr("tabindex");c||(q.width="100%",f[0].style.width="100%");h.each(ra(a,w),function(b,c){B=aa(a,b);c.style.width=a.aoColumns[B].sWidth});u&&I(function(a){a.style.width=""},R);f=p.outerWidth();if(""===c){r.width="100%";if(U&&(p.find("tbody").height()>j.offsetHeight||
"scroll"==l.css("overflow-y")))r.width=v(p.outerWidth()-b);f=p.outerWidth()}else""!==d&&(r.width=v(d),f=p.outerWidth());I(C,L);I(function(a){z.push(a.innerHTML);Ua.push(v(h(a).css("width")))},L);I(function(a,b){if(h.inArray(a,Xb)!==-1)a.style.width=Ua[b]},o);h(L).height(0);u&&(I(C,R),I(function(a){A.push(a.innerHTML);y.push(v(h(a).css("width")))},R),I(function(a,b){a.style.width=y[b]},Q),h(R).height(0));I(function(a,b){a.innerHTML='<div class="dataTables_sizing">'+z[b]+"</div>";a.childNodes[0].style.height=
"0";a.childNodes[0].style.overflow="hidden";a.style.width=Ua[b]},L);u&&I(function(a,b){a.innerHTML='<div class="dataTables_sizing">'+A[b]+"</div>";a.childNodes[0].style.height="0";a.childNodes[0].style.overflow="hidden";a.style.width=y[b]},R);if(p.outerWidth()<f){Q=j.scrollHeight>j.offsetHeight||"scroll"==l.css("overflow-y")?f+b:f;if(U&&(j.scrollHeight>j.offsetHeight||"scroll"==l.css("overflow-y")))r.width=v(Q-b);(""===c||""!==d)&&K(a,1,"Possible column misalignment",6)}else Q="100%";q.width=v(Q);
g.width=v(Q);u&&(a.nScrollFoot.style.width=v(Q));!e&&U&&(q.height=v(s.offsetHeight+b));c=p.outerWidth();m[0].style.width=v(c);i.width=v(c);d=p.height()>j.clientHeight||"scroll"==l.css("overflow-y");e="padding"+(x.bScrollbarLeft?"Left":"Right");i[e]=d?b+"px":"0px";u&&(n[0].style.width=v(c),t[0].style.width=v(c),t[0].style[e]=d?b+"px":"0px");p.children("colgroup").insertBefore(p.children("thead"));l.scroll();if((a.bSorted||a.bFiltered)&&!a._drawHold)j.scrollTop=0}}function I(a,b,c){for(var d=0,e=0,
f=b.length,g,j;e<f;){g=b[e].firstChild;for(j=c?c[e].firstChild:null;g;)1===g.nodeType&&(c?a(g,j,d):a(g,d),d++),g=g.nextSibling,j=c?j.nextSibling:null;e++}}function Fa(a){var b=a.nTable,c=a.aoColumns,d=a.oScroll,e=d.sY,f=d.sX,g=d.sXInner,j=c.length,i=ma(a,"bVisible"),m=h("th",a.nTHead),l=b.getAttribute("width"),k=b.parentNode,t=!1,n,o,p=a.oBrowser,d=p.bScrollOversize;(n=b.style.width)&&-1!==n.indexOf("%")&&(l=n);for(n=0;n<i.length;n++)o=c[i[n]],null!==o.sWidth&&(o.sWidth=Eb(o.sWidthOrig,k),t=!0);if(d||
!t&&!f&&!e&&j==V(a)&&j==m.length)for(n=0;n<j;n++)i=aa(a,n),null!==i&&(c[i].sWidth=v(m.eq(n).width()));else{j=h(b).clone().css("visibility","hidden").removeAttr("id");j.find("tbody tr").remove();var s=h("<tr/>").appendTo(j.find("tbody"));j.find("thead, tfoot").remove();j.append(h(a.nTHead).clone()).append(h(a.nTFoot).clone());j.find("tfoot th, tfoot td").css("width","");m=ra(a,j.find("thead")[0]);for(n=0;n<i.length;n++)o=c[i[n]],m[n].style.width=null!==o.sWidthOrig&&""!==o.sWidthOrig?v(o.sWidthOrig):
"",o.sWidthOrig&&f&&h(m[n]).append(h("<div/>").css({width:o.sWidthOrig,margin:0,padding:0,border:0,height:1}));if(a.aoData.length)for(n=0;n<i.length;n++)t=i[n],o=c[t],h(Fb(a,t)).clone(!1).append(o.sContentPadding).appendTo(s);h("[name]",j).removeAttr("name");o=h("<div/>").css(f||e?{position:"absolute",top:0,left:0,height:1,right:0,overflow:"hidden"}:{}).append(j).appendTo(k);f&&g?j.width(g):f?(j.css("width","auto"),j.removeAttr("width"),j.width()<k.clientWidth&&l&&j.width(k.clientWidth)):e?j.width(k.clientWidth):
l&&j.width(l);for(n=e=0;n<i.length;n++)k=h(m[n]),g=k.outerWidth()-k.width(),k=p.bBounding?Math.ceil(m[n].getBoundingClientRect().width):k.outerWidth(),e+=k,c[i[n]].sWidth=v(k-g);b.style.width=v(e);o.remove()}l&&(b.style.width=v(l));if((l||f)&&!a._reszEvt)b=function(){h(E).on("resize.DT-"+a.sInstance,Oa(function(){$(a)}))},d?setTimeout(b,1E3):b(),a._reszEvt=!0}function Eb(a,b){if(!a)return 0;var c=h("<div/>").css("width",v(a)).appendTo(b||H.body),d=c[0].offsetWidth;c.remove();return d}function Fb(a,
b){var c=Gb(a,b);if(0>c)return null;var d=a.aoData[c];return!d.nTr?h("<td/>").html(B(a,c,b,"display"))[0]:d.anCells[b]}function Gb(a,b){for(var c,d=-1,e=-1,f=0,g=a.aoData.length;f<g;f++)c=B(a,f,b,"display")+"",c=c.replace(Yb,""),c=c.replace(/&nbsp;/g," "),c.length>d&&(d=c.length,e=f);return e}function v(a){return null===a?"0px":"number"==typeof a?0>a?"0px":a+"px":a.match(/\d$/)?a+"px":a}function X(a){var b,c,d=[],e=a.aoColumns,f,g,j,i;b=a.aaSortingFixed;c=h.isPlainObject(b);var m=[];f=function(a){a.length&&
!h.isArray(a[0])?m.push(a):h.merge(m,a)};h.isArray(b)&&f(b);c&&b.pre&&f(b.pre);f(a.aaSorting);c&&b.post&&f(b.post);for(a=0;a<m.length;a++){i=m[a][0];f=e[i].aDataSort;b=0;for(c=f.length;b<c;b++)g=f[b],j=e[g].sType||"string",m[a]._idx===k&&(m[a]._idx=h.inArray(m[a][1],e[g].asSorting)),d.push({src:i,col:g,dir:m[a][1],index:m[a]._idx,type:j,formatter:n.ext.type.order[j+"-pre"]})}return d}function mb(a){var b,c,d=[],e=n.ext.type.order,f=a.aoData,g=0,j,i=a.aiDisplayMaster,h;Ga(a);h=X(a);b=0;for(c=h.length;b<
c;b++)j=h[b],j.formatter&&g++,Hb(a,j.col);if("ssp"!=y(a)&&0!==h.length){b=0;for(c=i.length;b<c;b++)d[i[b]]=b;g===h.length?i.sort(function(a,b){var c,e,g,j,i=h.length,k=f[a]._aSortData,n=f[b]._aSortData;for(g=0;g<i;g++)if(j=h[g],c=k[j.col],e=n[j.col],c=c<e?-1:c>e?1:0,0!==c)return"asc"===j.dir?c:-c;c=d[a];e=d[b];return c<e?-1:c>e?1:0}):i.sort(function(a,b){var c,g,j,i,k=h.length,n=f[a]._aSortData,o=f[b]._aSortData;for(j=0;j<k;j++)if(i=h[j],c=n[i.col],g=o[i.col],i=e[i.type+"-"+i.dir]||e["string-"+i.dir],
c=i(c,g),0!==c)return c;c=d[a];g=d[b];return c<g?-1:c>g?1:0})}a.bSorted=!0}function Ib(a){for(var b,c,d=a.aoColumns,e=X(a),a=a.oLanguage.oAria,f=0,g=d.length;f<g;f++){c=d[f];var j=c.asSorting;b=c.sTitle.replace(/<.*?>/g,"");var i=c.nTh;i.removeAttribute("aria-sort");c.bSortable&&(0<e.length&&e[0].col==f?(i.setAttribute("aria-sort","asc"==e[0].dir?"ascending":"descending"),c=j[e[0].index+1]||j[0]):c=j[0],b+="asc"===c?a.sSortAscending:a.sSortDescending);i.setAttribute("aria-label",b)}}function Va(a,
b,c,d){var e=a.aaSorting,f=a.aoColumns[b].asSorting,g=function(a,b){var c=a._idx;c===k&&(c=h.inArray(a[1],f));return c+1<f.length?c+1:b?null:0};"number"===typeof e[0]&&(e=a.aaSorting=[e]);c&&a.oFeatures.bSortMulti?(c=h.inArray(b,D(e,"0")),-1!==c?(b=g(e[c],!0),null===b&&1===e.length&&(b=0),null===b?e.splice(c,1):(e[c][1]=f[b],e[c]._idx=b)):(e.push([b,f[0],0]),e[e.length-1]._idx=0)):e.length&&e[0][0]==b?(b=g(e[0]),e.length=1,e[0][1]=f[b],e[0]._idx=b):(e.length=0,e.push([b,f[0]]),e[0]._idx=0);T(a);"function"==
typeof d&&d(a)}function Ma(a,b,c,d){var e=a.aoColumns[c];Wa(b,{},function(b){!1!==e.bSortable&&(a.oFeatures.bProcessing?(C(a,!0),setTimeout(function(){Va(a,c,b.shiftKey,d);"ssp"!==y(a)&&C(a,!1)},0)):Va(a,c,b.shiftKey,d))})}function wa(a){var b=a.aLastSort,c=a.oClasses.sSortColumn,d=X(a),e=a.oFeatures,f,g;if(e.bSort&&e.bSortClasses){e=0;for(f=b.length;e<f;e++)g=b[e].src,h(D(a.aoData,"anCells",g)).removeClass(c+(2>e?e+1:3));e=0;for(f=d.length;e<f;e++)g=d[e].src,h(D(a.aoData,"anCells",g)).addClass(c+
(2>e?e+1:3))}a.aLastSort=d}function Hb(a,b){var c=a.aoColumns[b],d=n.ext.order[c.sSortDataType],e;d&&(e=d.call(a.oInstance,a,b,ba(a,b)));for(var f,g=n.ext.type.order[c.sType+"-pre"],j=0,i=a.aoData.length;j<i;j++)if(c=a.aoData[j],c._aSortData||(c._aSortData=[]),!c._aSortData[b]||d)f=d?e[j]:B(a,j,b,"sort"),c._aSortData[b]=g?g(f):f}function xa(a){if(a.oFeatures.bStateSave&&!a.bDestroying){var b={time:+new Date,start:a._iDisplayStart,length:a._iDisplayLength,order:h.extend(!0,[],a.aaSorting),search:Ab(a.oPreviousSearch),
columns:h.map(a.aoColumns,function(b,d){return{visible:b.bVisible,search:Ab(a.aoPreSearchCols[d])}})};r(a,"aoStateSaveParams","stateSaveParams",[a,b]);a.oSavedState=b;a.fnStateSaveCallback.call(a.oInstance,a,b)}}function Jb(a,b,c){var d,e,f=a.aoColumns,b=function(b){if(b&&b.time){var g=r(a,"aoStateLoadParams","stateLoadParams",[a,b]);if(-1===h.inArray(!1,g)&&(g=a.iStateDuration,!(0<g&&b.time<+new Date-1E3*g)&&!(b.columns&&f.length!==b.columns.length))){a.oLoadedState=h.extend(!0,{},b);b.start!==k&&
(a._iDisplayStart=b.start,a.iInitDisplayStart=b.start);b.length!==k&&(a._iDisplayLength=b.length);b.order!==k&&(a.aaSorting=[],h.each(b.order,function(b,c){a.aaSorting.push(c[0]>=f.length?[0,c[1]]:c)}));b.search!==k&&h.extend(a.oPreviousSearch,Bb(b.search));if(b.columns){d=0;for(e=b.columns.length;d<e;d++)g=b.columns[d],g.visible!==k&&(f[d].bVisible=g.visible),g.search!==k&&h.extend(a.aoPreSearchCols[d],Bb(g.search))}r(a,"aoStateLoaded","stateLoaded",[a,b])}}c()};if(a.oFeatures.bStateSave){var g=
a.fnStateLoadCallback.call(a.oInstance,a,b);g!==k&&b(g)}else c()}function ya(a){var b=n.settings,a=h.inArray(a,D(b,"nTable"));return-1!==a?b[a]:null}function K(a,b,c,d){c="DataTables warning: "+(a?"table id="+a.sTableId+" - ":"")+c;d&&(c+=". For more information about this error, please see http://datatables.net/tn/"+d);if(b)E.console&&console.log&&console.log(c);else if(b=n.ext,b=b.sErrMode||b.errMode,a&&r(a,null,"error",[a,d,c]),"alert"==b)alert(c);else{if("throw"==b)throw Error(c);"function"==
typeof b&&b(a,d,c)}}function F(a,b,c,d){h.isArray(c)?h.each(c,function(c,d){h.isArray(d)?F(a,b,d[0],d[1]):F(a,b,d)}):(d===k&&(d=c),b[c]!==k&&(a[d]=b[c]))}function Xa(a,b,c){var d,e;for(e in b)b.hasOwnProperty(e)&&(d=b[e],h.isPlainObject(d)?(h.isPlainObject(a[e])||(a[e]={}),h.extend(!0,a[e],d)):a[e]=c&&"data"!==e&&"aaData"!==e&&h.isArray(d)?d.slice():d);return a}function Wa(a,b,c){h(a).on("click.DT",b,function(b){h(a).blur();c(b)}).on("keypress.DT",b,function(a){13===a.which&&(a.preventDefault(),c(a))}).on("selectstart.DT",
function(){return!1})}function z(a,b,c,d){c&&a[b].push({fn:c,sName:d})}function r(a,b,c,d){var e=[];b&&(e=h.map(a[b].slice().reverse(),function(b){return b.fn.apply(a.oInstance,d)}));null!==c&&(b=h.Event(c+".dt"),h(a.nTable).trigger(b,d),e.push(b.result));return e}function Sa(a){var b=a._iDisplayStart,c=a.fnDisplayEnd(),d=a._iDisplayLength;b>=c&&(b=c-d);b-=b%d;if(-1===d||0>b)b=0;a._iDisplayStart=b}function Na(a,b){var c=a.renderer,d=n.ext.renderer[b];return h.isPlainObject(c)&&c[b]?d[c[b]]||d._:"string"===
typeof c?d[c]||d._:d._}function y(a){return a.oFeatures.bServerSide?"ssp":a.ajax||a.sAjaxSource?"ajax":"dom"}function ia(a,b){var c=[],c=Kb.numbers_length,d=Math.floor(c/2);b<=c?c=Y(0,b):a<=d?(c=Y(0,c-2),c.push("ellipsis"),c.push(b-1)):(a>=b-1-d?c=Y(b-(c-2),b):(c=Y(a-d+2,a+d-1),c.push("ellipsis"),c.push(b-1)),c.splice(0,0,"ellipsis"),c.splice(0,0,0));c.DT_el="span";return c}function Da(a){h.each({num:function(b){return za(b,a)},"num-fmt":function(b){return za(b,a,Ya)},"html-num":function(b){return za(b,
a,Aa)},"html-num-fmt":function(b){return za(b,a,Aa,Ya)}},function(b,c){x.type.order[b+a+"-pre"]=c;b.match(/^html\-/)&&(x.type.search[b+a]=x.type.search.html)})}function Lb(a){return function(){var b=[ya(this[n.ext.iApiIndex])].concat(Array.prototype.slice.call(arguments));return n.ext.internal[a].apply(this,b)}}var n=function(a){this.$=function(a,b){return this.api(!0).$(a,b)};this._=function(a,b){return this.api(!0).rows(a,b).data()};this.api=function(a){return a?new s(ya(this[x.iApiIndex])):new s(this)};
this.fnAddData=function(a,b){var c=this.api(!0),d=h.isArray(a)&&(h.isArray(a[0])||h.isPlainObject(a[0]))?c.rows.add(a):c.row.add(a);(b===k||b)&&c.draw();return d.flatten().toArray()};this.fnAdjustColumnSizing=function(a){var b=this.api(!0).columns.adjust(),c=b.settings()[0],d=c.oScroll;a===k||a?b.draw(!1):(""!==d.sX||""!==d.sY)&&la(c)};this.fnClearTable=function(a){var b=this.api(!0).clear();(a===k||a)&&b.draw()};this.fnClose=function(a){this.api(!0).row(a).child.hide()};this.fnDeleteRow=function(a,
b,c){var d=this.api(!0),a=d.rows(a),e=a.settings()[0],h=e.aoData[a[0][0]];a.remove();b&&b.call(this,e,h);(c===k||c)&&d.draw();return h};this.fnDestroy=function(a){this.api(!0).destroy(a)};this.fnDraw=function(a){this.api(!0).draw(a)};this.fnFilter=function(a,b,c,d,e,h){e=this.api(!0);null===b||b===k?e.search(a,c,d,h):e.column(b).search(a,c,d,h);e.draw()};this.fnGetData=function(a,b){var c=this.api(!0);if(a!==k){var d=a.nodeName?a.nodeName.toLowerCase():"";return b!==k||"td"==d||"th"==d?c.cell(a,b).data():
c.row(a).data()||null}return c.data().toArray()};this.fnGetNodes=function(a){var b=this.api(!0);return a!==k?b.row(a).node():b.rows().nodes().flatten().toArray()};this.fnGetPosition=function(a){var b=this.api(!0),c=a.nodeName.toUpperCase();return"TR"==c?b.row(a).index():"TD"==c||"TH"==c?(a=b.cell(a).index(),[a.row,a.columnVisible,a.column]):null};this.fnIsOpen=function(a){return this.api(!0).row(a).child.isShown()};this.fnOpen=function(a,b,c){return this.api(!0).row(a).child(b,c).show().child()[0]};
this.fnPageChange=function(a,b){var c=this.api(!0).page(a);(b===k||b)&&c.draw(!1)};this.fnSetColumnVis=function(a,b,c){a=this.api(!0).column(a).visible(b);(c===k||c)&&a.columns.adjust().draw()};this.fnSettings=function(){return ya(this[x.iApiIndex])};this.fnSort=function(a){this.api(!0).order(a).draw()};this.fnSortListener=function(a,b,c){this.api(!0).order.listener(a,b,c)};this.fnUpdate=function(a,b,c,d,e){var h=this.api(!0);c===k||null===c?h.row(b).data(a):h.cell(b,c).data(a);(e===k||e)&&h.columns.adjust();
(d===k||d)&&h.draw();return 0};this.fnVersionCheck=x.fnVersionCheck;var b=this,c=a===k,d=this.length;c&&(a={});this.oApi=this.internal=x.internal;for(var e in n.ext.internal)e&&(this[e]=Lb(e));this.each(function(){var e={},g=1<d?Xa(e,a,!0):a,j=0,i,e=this.getAttribute("id"),m=!1,l=n.defaults,q=h(this);if("table"!=this.nodeName.toLowerCase())K(null,0,"Non-table node initialisation ("+this.nodeName+")",2);else{eb(l);fb(l.column);J(l,l,!0);J(l.column,l.column,!0);J(l,h.extend(g,q.data()));var t=n.settings,
j=0;for(i=t.length;j<i;j++){var o=t[j];if(o.nTable==this||o.nTHead&&o.nTHead.parentNode==this||o.nTFoot&&o.nTFoot.parentNode==this){var s=g.bRetrieve!==k?g.bRetrieve:l.bRetrieve;if(c||s)return o.oInstance;if(g.bDestroy!==k?g.bDestroy:l.bDestroy){o.oInstance.fnDestroy();break}else{K(o,0,"Cannot reinitialise DataTable",3);return}}if(o.sTableId==this.id){t.splice(j,1);break}}if(null===e||""===e)this.id=e="DataTables_Table_"+n.ext._unique++;var p=h.extend(!0,{},n.models.oSettings,{sDestroyWidth:q[0].style.width,
sInstance:e,sTableId:e});p.nTable=this;p.oApi=b.internal;p.oInit=g;t.push(p);p.oInstance=1===b.length?b:q.dataTable();eb(g);Ca(g.oLanguage);g.aLengthMenu&&!g.iDisplayLength&&(g.iDisplayLength=h.isArray(g.aLengthMenu[0])?g.aLengthMenu[0][0]:g.aLengthMenu[0]);g=Xa(h.extend(!0,{},l),g);F(p.oFeatures,g,"bPaginate bLengthChange bFilter bSort bSortMulti bInfo bProcessing bAutoWidth bSortClasses bServerSide bDeferRender".split(" "));F(p,g,["asStripeClasses","ajax","fnServerData","fnFormatNumber","sServerMethod",
"aaSorting","aaSortingFixed","aLengthMenu","sPaginationType","sAjaxSource","sAjaxDataProp","iStateDuration","sDom","bSortCellsTop","iTabIndex","fnStateLoadCallback","fnStateSaveCallback","renderer","searchDelay","rowId",["iCookieDuration","iStateDuration"],["oSearch","oPreviousSearch"],["aoSearchCols","aoPreSearchCols"],["iDisplayLength","_iDisplayLength"]]);F(p.oScroll,g,[["sScrollX","sX"],["sScrollXInner","sXInner"],["sScrollY","sY"],["bScrollCollapse","bCollapse"]]);F(p.oLanguage,g,"fnInfoCallback");
z(p,"aoDrawCallback",g.fnDrawCallback,"user");z(p,"aoServerParams",g.fnServerParams,"user");z(p,"aoStateSaveParams",g.fnStateSaveParams,"user");z(p,"aoStateLoadParams",g.fnStateLoadParams,"user");z(p,"aoStateLoaded",g.fnStateLoaded,"user");z(p,"aoRowCallback",g.fnRowCallback,"user");z(p,"aoRowCreatedCallback",g.fnCreatedRow,"user");z(p,"aoHeaderCallback",g.fnHeaderCallback,"user");z(p,"aoFooterCallback",g.fnFooterCallback,"user");z(p,"aoInitComplete",g.fnInitComplete,"user");z(p,"aoPreDrawCallback",
g.fnPreDrawCallback,"user");p.rowIdFn=S(g.rowId);gb(p);var u=p.oClasses;h.extend(u,n.ext.classes,g.oClasses);q.addClass(u.sTable);p.iInitDisplayStart===k&&(p.iInitDisplayStart=g.iDisplayStart,p._iDisplayStart=g.iDisplayStart);null!==g.iDeferLoading&&(p.bDeferLoading=!0,e=h.isArray(g.iDeferLoading),p._iRecordsDisplay=e?g.iDeferLoading[0]:g.iDeferLoading,p._iRecordsTotal=e?g.iDeferLoading[1]:g.iDeferLoading);var v=p.oLanguage;h.extend(!0,v,g.oLanguage);v.sUrl&&(h.ajax({dataType:"json",url:v.sUrl,success:function(a){Ca(a);
J(l.oLanguage,a);h.extend(true,v,a);ha(p)},error:function(){ha(p)}}),m=!0);null===g.asStripeClasses&&(p.asStripeClasses=[u.sStripeOdd,u.sStripeEven]);var e=p.asStripeClasses,x=q.children("tbody").find("tr").eq(0);-1!==h.inArray(!0,h.map(e,function(a){return x.hasClass(a)}))&&(h("tbody tr",this).removeClass(e.join(" ")),p.asDestroyStripes=e.slice());e=[];t=this.getElementsByTagName("thead");0!==t.length&&(ea(p.aoHeader,t[0]),e=ra(p));if(null===g.aoColumns){t=[];j=0;for(i=e.length;j<i;j++)t.push(null)}else t=
g.aoColumns;j=0;for(i=t.length;j<i;j++)Ea(p,e?e[j]:null);ib(p,g.aoColumnDefs,t,function(a,b){ka(p,a,b)});if(x.length){var w=function(a,b){return a.getAttribute("data-"+b)!==null?b:null};h(x[0]).children("th, td").each(function(a,b){var c=p.aoColumns[a];if(c.mData===a){var d=w(b,"sort")||w(b,"order"),e=w(b,"filter")||w(b,"search");if(d!==null||e!==null){c.mData={_:a+".display",sort:d!==null?a+".@data-"+d:k,type:d!==null?a+".@data-"+d:k,filter:e!==null?a+".@data-"+e:k};ka(p,a)}}})}var U=p.oFeatures,
e=function(){if(g.aaSorting===k){var a=p.aaSorting;j=0;for(i=a.length;j<i;j++)a[j][1]=p.aoColumns[j].asSorting[0]}wa(p);U.bSort&&z(p,"aoDrawCallback",function(){if(p.bSorted){var a=X(p),b={};h.each(a,function(a,c){b[c.src]=c.dir});r(p,null,"order",[p,a,b]);Ib(p)}});z(p,"aoDrawCallback",function(){(p.bSorted||y(p)==="ssp"||U.bDeferRender)&&wa(p)},"sc");var a=q.children("caption").each(function(){this._captionSide=h(this).css("caption-side")}),b=q.children("thead");b.length===0&&(b=h("<thead/>").appendTo(q));
p.nTHead=b[0];b=q.children("tbody");b.length===0&&(b=h("<tbody/>").appendTo(q));p.nTBody=b[0];b=q.children("tfoot");if(b.length===0&&a.length>0&&(p.oScroll.sX!==""||p.oScroll.sY!==""))b=h("<tfoot/>").appendTo(q);if(b.length===0||b.children().length===0)q.addClass(u.sNoFooter);else if(b.length>0){p.nTFoot=b[0];ea(p.aoFooter,p.nTFoot)}if(g.aaData)for(j=0;j<g.aaData.length;j++)O(p,g.aaData[j]);else(p.bDeferLoading||y(p)=="dom")&&na(p,h(p.nTBody).children("tr"));p.aiDisplay=p.aiDisplayMaster.slice();
p.bInitialised=true;m===false&&ha(p)};g.bStateSave?(U.bStateSave=!0,z(p,"aoDrawCallback",xa,"state_save"),Jb(p,g,e)):e()}});b=null;return this},x,s,o,u,Za={},Mb=/[\r\n]/g,Aa=/<.*?>/g,Zb=/^\d{2,4}[\.\/\-]\d{1,2}[\.\/\-]\d{1,2}([T ]{1}\d{1,2}[:\.]\d{2}([\.:]\d{2})?)?$/,$b=RegExp("(\\/|\\.|\\*|\\+|\\?|\\||\\(|\\)|\\[|\\]|\\{|\\}|\\\\|\\$|\\^|\\-)","g"),Ya=/[',$£€¥%\u2009\u202F\u20BD\u20a9\u20BArfkɃΞ]/gi,M=function(a){return!a||!0===a||"-"===a?!0:!1},Nb=function(a){var b=parseInt(a,10);return!isNaN(b)&&
isFinite(a)?b:null},Ob=function(a,b){Za[b]||(Za[b]=RegExp(Qa(b),"g"));return"string"===typeof a&&"."!==b?a.replace(/\./g,"").replace(Za[b],"."):a},$a=function(a,b,c){var d="string"===typeof a;if(M(a))return!0;b&&d&&(a=Ob(a,b));c&&d&&(a=a.replace(Ya,""));return!isNaN(parseFloat(a))&&isFinite(a)},Pb=function(a,b,c){return M(a)?!0:!(M(a)||"string"===typeof a)?null:$a(a.replace(Aa,""),b,c)?!0:null},D=function(a,b,c){var d=[],e=0,f=a.length;if(c!==k)for(;e<f;e++)a[e]&&a[e][b]&&d.push(a[e][b][c]);else for(;e<
f;e++)a[e]&&d.push(a[e][b]);return d},ja=function(a,b,c,d){var e=[],f=0,g=b.length;if(d!==k)for(;f<g;f++)a[b[f]][c]&&e.push(a[b[f]][c][d]);else for(;f<g;f++)e.push(a[b[f]][c]);return e},Y=function(a,b){var c=[],d;b===k?(b=0,d=a):(d=b,b=a);for(var e=b;e<d;e++)c.push(e);return c},Qb=function(a){for(var b=[],c=0,d=a.length;c<d;c++)a[c]&&b.push(a[c]);return b},qa=function(a){var b;a:{if(!(2>a.length)){b=a.slice().sort();for(var c=b[0],d=1,e=b.length;d<e;d++){if(b[d]===c){b=!1;break a}c=b[d]}}b=!0}if(b)return a.slice();
b=[];var e=a.length,f,g=0,d=0;a:for(;d<e;d++){c=a[d];for(f=0;f<g;f++)if(b[f]===c)continue a;b.push(c);g++}return b};n.util={throttle:function(a,b){var c=b!==k?b:200,d,e;return function(){var b=this,g=+new Date,j=arguments;d&&g<d+c?(clearTimeout(e),e=setTimeout(function(){d=k;a.apply(b,j)},c)):(d=g,a.apply(b,j))}},escapeRegex:function(a){return a.replace($b,"\\$1")}};var A=function(a,b,c){a[b]!==k&&(a[c]=a[b])},ca=/\[.*?\]$/,W=/\(\)$/,Qa=n.util.escapeRegex,va=h("<div>")[0],Wb=va.textContent!==k,Yb=
/<.*?>/g,Oa=n.util.throttle,Rb=[],w=Array.prototype,ac=function(a){var b,c,d=n.settings,e=h.map(d,function(a){return a.nTable});if(a){if(a.nTable&&a.oApi)return[a];if(a.nodeName&&"table"===a.nodeName.toLowerCase())return b=h.inArray(a,e),-1!==b?[d[b]]:null;if(a&&"function"===typeof a.settings)return a.settings().toArray();"string"===typeof a?c=h(a):a instanceof h&&(c=a)}else return[];if(c)return c.map(function(){b=h.inArray(this,e);return-1!==b?d[b]:null}).toArray()};s=function(a,b){if(!(this instanceof
s))return new s(a,b);var c=[],d=function(a){(a=ac(a))&&(c=c.concat(a))};if(h.isArray(a))for(var e=0,f=a.length;e<f;e++)d(a[e]);else d(a);this.context=qa(c);b&&h.merge(this,b);this.selector={rows:null,cols:null,opts:null};s.extend(this,this,Rb)};n.Api=s;h.extend(s.prototype,{any:function(){return 0!==this.count()},concat:w.concat,context:[],count:function(){return this.flatten().length},each:function(a){for(var b=0,c=this.length;b<c;b++)a.call(this,this[b],b,this);return this},eq:function(a){var b=
this.context;return b.length>a?new s(b[a],this[a]):null},filter:function(a){var b=[];if(w.filter)b=w.filter.call(this,a,this);else for(var c=0,d=this.length;c<d;c++)a.call(this,this[c],c,this)&&b.push(this[c]);return new s(this.context,b)},flatten:function(){var a=[];return new s(this.context,a.concat.apply(a,this.toArray()))},join:w.join,indexOf:w.indexOf||function(a,b){for(var c=b||0,d=this.length;c<d;c++)if(this[c]===a)return c;return-1},iterator:function(a,b,c,d){var e=[],f,g,j,h,m,l=this.context,
n,o,u=this.selector;"string"===typeof a&&(d=c,c=b,b=a,a=!1);g=0;for(j=l.length;g<j;g++){var r=new s(l[g]);if("table"===b)f=c.call(r,l[g],g),f!==k&&e.push(f);else if("columns"===b||"rows"===b)f=c.call(r,l[g],this[g],g),f!==k&&e.push(f);else if("column"===b||"column-rows"===b||"row"===b||"cell"===b){o=this[g];"column-rows"===b&&(n=Ba(l[g],u.opts));h=0;for(m=o.length;h<m;h++)f=o[h],f="cell"===b?c.call(r,l[g],f.row,f.column,g,h):c.call(r,l[g],f,g,h,n),f!==k&&e.push(f)}}return e.length||d?(a=new s(l,a?
e.concat.apply([],e):e),b=a.selector,b.rows=u.rows,b.cols=u.cols,b.opts=u.opts,a):this},lastIndexOf:w.lastIndexOf||function(a,b){return this.indexOf.apply(this.toArray.reverse(),arguments)},length:0,map:function(a){var b=[];if(w.map)b=w.map.call(this,a,this);else for(var c=0,d=this.length;c<d;c++)b.push(a.call(this,this[c],c));return new s(this.context,b)},pluck:function(a){return this.map(function(b){return b[a]})},pop:w.pop,push:w.push,reduce:w.reduce||function(a,b){return hb(this,a,b,0,this.length,
1)},reduceRight:w.reduceRight||function(a,b){return hb(this,a,b,this.length-1,-1,-1)},reverse:w.reverse,selector:null,shift:w.shift,slice:function(){return new s(this.context,this)},sort:w.sort,splice:w.splice,toArray:function(){return w.slice.call(this)},to$:function(){return h(this)},toJQuery:function(){return h(this)},unique:function(){return new s(this.context,qa(this))},unshift:w.unshift});s.extend=function(a,b,c){if(c.length&&b&&(b instanceof s||b.__dt_wrapper)){var d,e,f,g=function(a,b,c){return function(){var d=
b.apply(a,arguments);s.extend(d,d,c.methodExt);return d}};d=0;for(e=c.length;d<e;d++)f=c[d],b[f.name]="function"===typeof f.val?g(a,f.val,f):h.isPlainObject(f.val)?{}:f.val,b[f.name].__dt_wrapper=!0,s.extend(a,b[f.name],f.propExt)}};s.register=o=function(a,b){if(h.isArray(a))for(var c=0,d=a.length;c<d;c++)s.register(a[c],b);else for(var e=a.split("."),f=Rb,g,j,c=0,d=e.length;c<d;c++){g=(j=-1!==e[c].indexOf("()"))?e[c].replace("()",""):e[c];var i;a:{i=0;for(var m=f.length;i<m;i++)if(f[i].name===g){i=
f[i];break a}i=null}i||(i={name:g,val:{},methodExt:[],propExt:[]},f.push(i));c===d-1?i.val=b:f=j?i.methodExt:i.propExt}};s.registerPlural=u=function(a,b,c){s.register(a,c);s.register(b,function(){var a=c.apply(this,arguments);return a===this?this:a instanceof s?a.length?h.isArray(a[0])?new s(a.context,a[0]):a[0]:k:a})};o("tables()",function(a){var b;if(a){b=s;var c=this.context;if("number"===typeof a)a=[c[a]];else var d=h.map(c,function(a){return a.nTable}),a=h(d).filter(a).map(function(){var a=h.inArray(this,
d);return c[a]}).toArray();b=new b(a)}else b=this;return b});o("table()",function(a){var a=this.tables(a),b=a.context;return b.length?new s(b[0]):a});u("tables().nodes()","table().node()",function(){return this.iterator("table",function(a){return a.nTable},1)});u("tables().body()","table().body()",function(){return this.iterator("table",function(a){return a.nTBody},1)});u("tables().header()","table().header()",function(){return this.iterator("table",function(a){return a.nTHead},1)});u("tables().footer()",
"table().footer()",function(){return this.iterator("table",function(a){return a.nTFoot},1)});u("tables().containers()","table().container()",function(){return this.iterator("table",function(a){return a.nTableWrapper},1)});o("draw()",function(a){return this.iterator("table",function(b){"page"===a?P(b):("string"===typeof a&&(a="full-hold"===a?!1:!0),T(b,!1===a))})});o("page()",function(a){return a===k?this.page.info().page:this.iterator("table",function(b){Ta(b,a)})});o("page.info()",function(){if(0===
this.context.length)return k;var a=this.context[0],b=a._iDisplayStart,c=a.oFeatures.bPaginate?a._iDisplayLength:-1,d=a.fnRecordsDisplay(),e=-1===c;return{page:e?0:Math.floor(b/c),pages:e?1:Math.ceil(d/c),start:b,end:a.fnDisplayEnd(),length:c,recordsTotal:a.fnRecordsTotal(),recordsDisplay:d,serverSide:"ssp"===y(a)}});o("page.len()",function(a){return a===k?0!==this.context.length?this.context[0]._iDisplayLength:k:this.iterator("table",function(b){Ra(b,a)})});var Sb=function(a,b,c){if(c){var d=new s(a);
d.one("draw",function(){c(d.ajax.json())})}if("ssp"==y(a))T(a,b);else{C(a,!0);var e=a.jqXHR;e&&4!==e.readyState&&e.abort();sa(a,[],function(c){oa(a);for(var c=ta(a,c),d=0,e=c.length;d<e;d++)O(a,c[d]);T(a,b);C(a,!1)})}};o("ajax.json()",function(){var a=this.context;if(0<a.length)return a[0].json});o("ajax.params()",function(){var a=this.context;if(0<a.length)return a[0].oAjaxData});o("ajax.reload()",function(a,b){return this.iterator("table",function(c){Sb(c,!1===b,a)})});o("ajax.url()",function(a){var b=
this.context;if(a===k){if(0===b.length)return k;b=b[0];return b.ajax?h.isPlainObject(b.ajax)?b.ajax.url:b.ajax:b.sAjaxSource}return this.iterator("table",function(b){h.isPlainObject(b.ajax)?b.ajax.url=a:b.ajax=a})});o("ajax.url().load()",function(a,b){return this.iterator("table",function(c){Sb(c,!1===b,a)})});var ab=function(a,b,c,d,e){var f=[],g,j,i,m,l,n;i=typeof b;if(!b||"string"===i||"function"===i||b.length===k)b=[b];i=0;for(m=b.length;i<m;i++){j=b[i]&&b[i].split&&!b[i].match(/[\[\(:]/)?b[i].split(","):
[b[i]];l=0;for(n=j.length;l<n;l++)(g=c("string"===typeof j[l]?h.trim(j[l]):j[l]))&&g.length&&(f=f.concat(g))}a=x.selector[a];if(a.length){i=0;for(m=a.length;i<m;i++)f=a[i](d,e,f)}return qa(f)},bb=function(a){a||(a={});a.filter&&a.search===k&&(a.search=a.filter);return h.extend({search:"none",order:"current",page:"all"},a)},cb=function(a){for(var b=0,c=a.length;b<c;b++)if(0<a[b].length)return a[0]=a[b],a[0].length=1,a.length=1,a.context=[a.context[b]],a;a.length=0;return a},Ba=function(a,b){var c,
d,e,f=[],g=a.aiDisplay;e=a.aiDisplayMaster;var j=b.search;c=b.order;d=b.page;if("ssp"==y(a))return"removed"===j?[]:Y(0,e.length);if("current"==d){c=a._iDisplayStart;for(d=a.fnDisplayEnd();c<d;c++)f.push(g[c])}else if("current"==c||"applied"==c)if("none"==j)f=e.slice();else if("applied"==j)f=g.slice();else{if("removed"==j){var i={};c=0;for(d=g.length;c<d;c++)i[g[c]]=null;f=h.map(e,function(a){return!i.hasOwnProperty(a)?a:null})}}else if("index"==c||"original"==c){c=0;for(d=a.aoData.length;c<d;c++)"none"==
j?f.push(c):(e=h.inArray(c,g),(-1===e&&"removed"==j||0<=e&&"applied"==j)&&f.push(c))}return f};o("rows()",function(a,b){a===k?a="":h.isPlainObject(a)&&(b=a,a="");var b=bb(b),c=this.iterator("table",function(c){var e=b,f;return ab("row",a,function(a){var b=Nb(a),i=c.aoData;if(b!==null&&!e)return[b];f||(f=Ba(c,e));if(b!==null&&h.inArray(b,f)!==-1)return[b];if(a===null||a===k||a==="")return f;if(typeof a==="function")return h.map(f,function(b){var c=i[b];return a(b,c._aData,c.nTr)?b:null});if(a.nodeName){var b=
a._DT_RowIndex,m=a._DT_CellIndex;if(b!==k)return i[b]&&i[b].nTr===a?[b]:[];if(m)return i[m.row]&&i[m.row].nTr===a?[m.row]:[];b=h(a).closest("*[data-dt-row]");return b.length?[b.data("dt-row")]:[]}if(typeof a==="string"&&a.charAt(0)==="#"){b=c.aIds[a.replace(/^#/,"")];if(b!==k)return[b.idx]}b=Qb(ja(c.aoData,f,"nTr"));return h(b).filter(a).map(function(){return this._DT_RowIndex}).toArray()},c,e)},1);c.selector.rows=a;c.selector.opts=b;return c});o("rows().nodes()",function(){return this.iterator("row",
function(a,b){return a.aoData[b].nTr||k},1)});o("rows().data()",function(){return this.iterator(!0,"rows",function(a,b){return ja(a.aoData,b,"_aData")},1)});u("rows().cache()","row().cache()",function(a){return this.iterator("row",function(b,c){var d=b.aoData[c];return"search"===a?d._aFilterData:d._aSortData},1)});u("rows().invalidate()","row().invalidate()",function(a){return this.iterator("row",function(b,c){da(b,c,a)})});u("rows().indexes()","row().index()",function(){return this.iterator("row",
function(a,b){return b},1)});u("rows().ids()","row().id()",function(a){for(var b=[],c=this.context,d=0,e=c.length;d<e;d++)for(var f=0,g=this[d].length;f<g;f++){var h=c[d].rowIdFn(c[d].aoData[this[d][f]]._aData);b.push((!0===a?"#":"")+h)}return new s(c,b)});u("rows().remove()","row().remove()",function(){var a=this;this.iterator("row",function(b,c,d){var e=b.aoData,f=e[c],g,h,i,m,l;e.splice(c,1);g=0;for(h=e.length;g<h;g++)if(i=e[g],l=i.anCells,null!==i.nTr&&(i.nTr._DT_RowIndex=g),null!==l){i=0;for(m=
l.length;i<m;i++)l[i]._DT_CellIndex.row=g}pa(b.aiDisplayMaster,c);pa(b.aiDisplay,c);pa(a[d],c,!1);0<b._iRecordsDisplay&&b._iRecordsDisplay--;Sa(b);c=b.rowIdFn(f._aData);c!==k&&delete b.aIds[c]});this.iterator("table",function(a){for(var c=0,d=a.aoData.length;c<d;c++)a.aoData[c].idx=c});return this});o("rows.add()",function(a){var b=this.iterator("table",function(b){var c,f,g,h=[];f=0;for(g=a.length;f<g;f++)c=a[f],c.nodeName&&"TR"===c.nodeName.toUpperCase()?h.push(na(b,c)[0]):h.push(O(b,c));return h},
1),c=this.rows(-1);c.pop();h.merge(c,b);return c});o("row()",function(a,b){return cb(this.rows(a,b))});o("row().data()",function(a){var b=this.context;if(a===k)return b.length&&this.length?b[0].aoData[this[0]]._aData:k;var c=b[0].aoData[this[0]];c._aData=a;h.isArray(a)&&c.nTr.id&&N(b[0].rowId)(a,c.nTr.id);da(b[0],this[0],"data");return this});o("row().node()",function(){var a=this.context;return a.length&&this.length?a[0].aoData[this[0]].nTr||null:null});o("row.add()",function(a){a instanceof h&&
a.length&&(a=a[0]);var b=this.iterator("table",function(b){return a.nodeName&&"TR"===a.nodeName.toUpperCase()?na(b,a)[0]:O(b,a)});return this.row(b[0])});var db=function(a,b){var c=a.context;if(c.length&&(c=c[0].aoData[b!==k?b:a[0]])&&c._details)c._details.remove(),c._detailsShow=k,c._details=k},Tb=function(a,b){var c=a.context;if(c.length&&a.length){var d=c[0].aoData[a[0]];if(d._details){(d._detailsShow=b)?d._details.insertAfter(d.nTr):d._details.detach();var e=c[0],f=new s(e),g=e.aoData;f.off("draw.dt.DT_details column-visibility.dt.DT_details destroy.dt.DT_details");
0<D(g,"_details").length&&(f.on("draw.dt.DT_details",function(a,b){e===b&&f.rows({page:"current"}).eq(0).each(function(a){a=g[a];a._detailsShow&&a._details.insertAfter(a.nTr)})}),f.on("column-visibility.dt.DT_details",function(a,b){if(e===b)for(var c,d=V(b),f=0,h=g.length;f<h;f++)c=g[f],c._details&&c._details.children("td[colspan]").attr("colspan",d)}),f.on("destroy.dt.DT_details",function(a,b){if(e===b)for(var c=0,d=g.length;c<d;c++)g[c]._details&&db(f,c)}))}}};o("row().child()",function(a,b){var c=
this.context;if(a===k)return c.length&&this.length?c[0].aoData[this[0]]._details:k;if(!0===a)this.child.show();else if(!1===a)db(this);else if(c.length&&this.length){var d=c[0],c=c[0].aoData[this[0]],e=[],f=function(a,b){if(h.isArray(a)||a instanceof h)for(var c=0,k=a.length;c<k;c++)f(a[c],b);else a.nodeName&&"tr"===a.nodeName.toLowerCase()?e.push(a):(c=h("<tr><td/></tr>").addClass(b),h("td",c).addClass(b).html(a)[0].colSpan=V(d),e.push(c[0]))};f(a,b);c._details&&c._details.detach();c._details=h(e);
c._detailsShow&&c._details.insertAfter(c.nTr)}return this});o(["row().child.show()","row().child().show()"],function(){Tb(this,!0);return this});o(["row().child.hide()","row().child().hide()"],function(){Tb(this,!1);return this});o(["row().child.remove()","row().child().remove()"],function(){db(this);return this});o("row().child.isShown()",function(){var a=this.context;return a.length&&this.length?a[0].aoData[this[0]]._detailsShow||!1:!1});var bc=/^([^:]+):(name|visIdx|visible)$/,Ub=function(a,b,
c,d,e){for(var c=[],d=0,f=e.length;d<f;d++)c.push(B(a,e[d],b));return c};o("columns()",function(a,b){a===k?a="":h.isPlainObject(a)&&(b=a,a="");var b=bb(b),c=this.iterator("table",function(c){var e=a,f=b,g=c.aoColumns,j=D(g,"sName"),i=D(g,"nTh");return ab("column",e,function(a){var b=Nb(a);if(a==="")return Y(g.length);if(b!==null)return[b>=0?b:g.length+b];if(typeof a==="function"){var e=Ba(c,f);return h.map(g,function(b,f){return a(f,Ub(c,f,0,0,e),i[f])?f:null})}var k=typeof a==="string"?a.match(bc):
"";if(k)switch(k[2]){case "visIdx":case "visible":b=parseInt(k[1],10);if(b<0){var n=h.map(g,function(a,b){return a.bVisible?b:null});return[n[n.length+b]]}return[aa(c,b)];case "name":return h.map(j,function(a,b){return a===k[1]?b:null});default:return[]}if(a.nodeName&&a._DT_CellIndex)return[a._DT_CellIndex.column];b=h(i).filter(a).map(function(){return h.inArray(this,i)}).toArray();if(b.length||!a.nodeName)return b;b=h(a).closest("*[data-dt-column]");return b.length?[b.data("dt-column")]:[]},c,f)},
1);c.selector.cols=a;c.selector.opts=b;return c});u("columns().header()","column().header()",function(){return this.iterator("column",function(a,b){return a.aoColumns[b].nTh},1)});u("columns().footer()","column().footer()",function(){return this.iterator("column",function(a,b){return a.aoColumns[b].nTf},1)});u("columns().data()","column().data()",function(){return this.iterator("column-rows",Ub,1)});u("columns().dataSrc()","column().dataSrc()",function(){return this.iterator("column",function(a,b){return a.aoColumns[b].mData},
1)});u("columns().cache()","column().cache()",function(a){return this.iterator("column-rows",function(b,c,d,e,f){return ja(b.aoData,f,"search"===a?"_aFilterData":"_aSortData",c)},1)});u("columns().nodes()","column().nodes()",function(){return this.iterator("column-rows",function(a,b,c,d,e){return ja(a.aoData,e,"anCells",b)},1)});u("columns().visible()","column().visible()",function(a,b){var c=this.iterator("column",function(b,c){if(a===k)return b.aoColumns[c].bVisible;var f=b.aoColumns,g=f[c],j=b.aoData,
i,m,l;if(a!==k&&g.bVisible!==a){if(a){var n=h.inArray(!0,D(f,"bVisible"),c+1);i=0;for(m=j.length;i<m;i++)l=j[i].nTr,f=j[i].anCells,l&&l.insertBefore(f[c],f[n]||null)}else h(D(b.aoData,"anCells",c)).detach();g.bVisible=a;fa(b,b.aoHeader);fa(b,b.aoFooter);b.aiDisplay.length||h(b.nTBody).find("td[colspan]").attr("colspan",V(b));xa(b)}});a!==k&&(this.iterator("column",function(c,e){r(c,null,"column-visibility",[c,e,a,b])}),(b===k||b)&&this.columns.adjust());return c});u("columns().indexes()","column().index()",
function(a){return this.iterator("column",function(b,c){return"visible"===a?ba(b,c):c},1)});o("columns.adjust()",function(){return this.iterator("table",function(a){$(a)},1)});o("column.index()",function(a,b){if(0!==this.context.length){var c=this.context[0];if("fromVisible"===a||"toData"===a)return aa(c,b);if("fromData"===a||"toVisible"===a)return ba(c,b)}});o("column()",function(a,b){return cb(this.columns(a,b))});o("cells()",function(a,b,c){h.isPlainObject(a)&&(a.row===k?(c=a,a=null):(c=b,b=null));
h.isPlainObject(b)&&(c=b,b=null);if(null===b||b===k)return this.iterator("table",function(b){var d=a,e=bb(c),f=b.aoData,g=Ba(b,e),j=Qb(ja(f,g,"anCells")),i=h([].concat.apply([],j)),l,m=b.aoColumns.length,n,o,u,s,r,v;return ab("cell",d,function(a){var c=typeof a==="function";if(a===null||a===k||c){n=[];o=0;for(u=g.length;o<u;o++){l=g[o];for(s=0;s<m;s++){r={row:l,column:s};if(c){v=f[l];a(r,B(b,l,s),v.anCells?v.anCells[s]:null)&&n.push(r)}else n.push(r)}}return n}if(h.isPlainObject(a))return a.column!==
k&&a.row!==k&&h.inArray(a.row,g)!==-1?[a]:[];c=i.filter(a).map(function(a,b){return{row:b._DT_CellIndex.row,column:b._DT_CellIndex.column}}).toArray();if(c.length||!a.nodeName)return c;v=h(a).closest("*[data-dt-row]");return v.length?[{row:v.data("dt-row"),column:v.data("dt-column")}]:[]},b,e)});var d=this.columns(b),e=this.rows(a),f,g,j,i,m;this.iterator("table",function(a,b){f=[];g=0;for(j=e[b].length;g<j;g++){i=0;for(m=d[b].length;i<m;i++)f.push({row:e[b][g],column:d[b][i]})}},1);var l=this.cells(f,
c);h.extend(l.selector,{cols:b,rows:a,opts:c});return l});u("cells().nodes()","cell().node()",function(){return this.iterator("cell",function(a,b,c){return(a=a.aoData[b])&&a.anCells?a.anCells[c]:k},1)});o("cells().data()",function(){return this.iterator("cell",function(a,b,c){return B(a,b,c)},1)});u("cells().cache()","cell().cache()",function(a){a="search"===a?"_aFilterData":"_aSortData";return this.iterator("cell",function(b,c,d){return b.aoData[c][a][d]},1)});u("cells().render()","cell().render()",
function(a){return this.iterator("cell",function(b,c,d){return B(b,c,d,a)},1)});u("cells().indexes()","cell().index()",function(){return this.iterator("cell",function(a,b,c){return{row:b,column:c,columnVisible:ba(a,c)}},1)});u("cells().invalidate()","cell().invalidate()",function(a){return this.iterator("cell",function(b,c,d){da(b,c,a,d)})});o("cell()",function(a,b,c){return cb(this.cells(a,b,c))});o("cell().data()",function(a){var b=this.context,c=this[0];if(a===k)return b.length&&c.length?B(b[0],
c[0].row,c[0].column):k;jb(b[0],c[0].row,c[0].column,a);da(b[0],c[0].row,"data",c[0].column);return this});o("order()",function(a,b){var c=this.context;if(a===k)return 0!==c.length?c[0].aaSorting:k;"number"===typeof a?a=[[a,b]]:a.length&&!h.isArray(a[0])&&(a=Array.prototype.slice.call(arguments));return this.iterator("table",function(b){b.aaSorting=a.slice()})});o("order.listener()",function(a,b,c){return this.iterator("table",function(d){Ma(d,a,b,c)})});o("order.fixed()",function(a){if(!a){var b=
this.context,b=b.length?b[0].aaSortingFixed:k;return h.isArray(b)?{pre:b}:b}return this.iterator("table",function(b){b.aaSortingFixed=h.extend(!0,{},a)})});o(["columns().order()","column().order()"],function(a){var b=this;return this.iterator("table",function(c,d){var e=[];h.each(b[d],function(b,c){e.push([c,a])});c.aaSorting=e})});o("search()",function(a,b,c,d){var e=this.context;return a===k?0!==e.length?e[0].oPreviousSearch.sSearch:k:this.iterator("table",function(e){e.oFeatures.bFilter&&ga(e,
h.extend({},e.oPreviousSearch,{sSearch:a+"",bRegex:null===b?!1:b,bSmart:null===c?!0:c,bCaseInsensitive:null===d?!0:d}),1)})});u("columns().search()","column().search()",function(a,b,c,d){return this.iterator("column",function(e,f){var g=e.aoPreSearchCols;if(a===k)return g[f].sSearch;e.oFeatures.bFilter&&(h.extend(g[f],{sSearch:a+"",bRegex:null===b?!1:b,bSmart:null===c?!0:c,bCaseInsensitive:null===d?!0:d}),ga(e,e.oPreviousSearch,1))})});o("state()",function(){return this.context.length?this.context[0].oSavedState:
null});o("state.clear()",function(){return this.iterator("table",function(a){a.fnStateSaveCallback.call(a.oInstance,a,{})})});o("state.loaded()",function(){return this.context.length?this.context[0].oLoadedState:null});o("state.save()",function(){return this.iterator("table",function(a){xa(a)})});n.versionCheck=n.fnVersionCheck=function(a){for(var b=n.version.split("."),a=a.split("."),c,d,e=0,f=a.length;e<f;e++)if(c=parseInt(b[e],10)||0,d=parseInt(a[e],10)||0,c!==d)return c>d;return!0};n.isDataTable=
n.fnIsDataTable=function(a){var b=h(a).get(0),c=!1;if(a instanceof n.Api)return!0;h.each(n.settings,function(a,e){var f=e.nScrollHead?h("table",e.nScrollHead)[0]:null,g=e.nScrollFoot?h("table",e.nScrollFoot)[0]:null;if(e.nTable===b||f===b||g===b)c=!0});return c};n.tables=n.fnTables=function(a){var b=!1;h.isPlainObject(a)&&(b=a.api,a=a.visible);var c=h.map(n.settings,function(b){if(!a||a&&h(b.nTable).is(":visible"))return b.nTable});return b?new s(c):c};n.camelToHungarian=J;o("$()",function(a,b){var c=
this.rows(b).nodes(),c=h(c);return h([].concat(c.filter(a).toArray(),c.find(a).toArray()))});h.each(["on","one","off"],function(a,b){o(b+"()",function(){var a=Array.prototype.slice.call(arguments);a[0]=h.map(a[0].split(/\s/),function(a){return!a.match(/\.dt\b/)?a+".dt":a}).join(" ");var d=h(this.tables().nodes());d[b].apply(d,a);return this})});o("clear()",function(){return this.iterator("table",function(a){oa(a)})});o("settings()",function(){return new s(this.context,this.context)});o("init()",function(){var a=
this.context;return a.length?a[0].oInit:null});o("data()",function(){return this.iterator("table",function(a){return D(a.aoData,"_aData")}).flatten()});o("destroy()",function(a){a=a||!1;return this.iterator("table",function(b){var c=b.nTableWrapper.parentNode,d=b.oClasses,e=b.nTable,f=b.nTBody,g=b.nTHead,j=b.nTFoot,i=h(e),f=h(f),k=h(b.nTableWrapper),l=h.map(b.aoData,function(a){return a.nTr}),o;b.bDestroying=!0;r(b,"aoDestroyCallback","destroy",[b]);a||(new s(b)).columns().visible(!0);k.off(".DT").find(":not(tbody *)").off(".DT");
h(E).off(".DT-"+b.sInstance);e!=g.parentNode&&(i.children("thead").detach(),i.append(g));j&&e!=j.parentNode&&(i.children("tfoot").detach(),i.append(j));b.aaSorting=[];b.aaSortingFixed=[];wa(b);h(l).removeClass(b.asStripeClasses.join(" "));h("th, td",g).removeClass(d.sSortable+" "+d.sSortableAsc+" "+d.sSortableDesc+" "+d.sSortableNone);f.children().detach();f.append(l);g=a?"remove":"detach";i[g]();k[g]();!a&&c&&(c.insertBefore(e,b.nTableReinsertBefore),i.css("width",b.sDestroyWidth).removeClass(d.sTable),
(o=b.asDestroyStripes.length)&&f.children().each(function(a){h(this).addClass(b.asDestroyStripes[a%o])}));c=h.inArray(b,n.settings);-1!==c&&n.settings.splice(c,1)})});h.each(["column","row","cell"],function(a,b){o(b+"s().every()",function(a){var d=this.selector.opts,e=this;return this.iterator(b,function(f,g,h,i,m){a.call(e[b](g,"cell"===b?h:d,"cell"===b?d:k),g,h,i,m)})})});o("i18n()",function(a,b,c){var d=this.context[0],a=S(a)(d.oLanguage);a===k&&(a=b);c!==k&&h.isPlainObject(a)&&(a=a[c]!==k?a[c]:
a._);return a.replace("%d",c)});n.version="1.10.18";n.settings=[];n.models={};n.models.oSearch={bCaseInsensitive:!0,sSearch:"",bRegex:!1,bSmart:!0};n.models.oRow={nTr:null,anCells:null,_aData:[],_aSortData:null,_aFilterData:null,_sFilterRow:null,_sRowStripe:"",src:null,idx:-1};n.models.oColumn={idx:null,aDataSort:null,asSorting:null,bSearchable:null,bSortable:null,bVisible:null,_sManualType:null,_bAttrSrc:!1,fnCreatedCell:null,fnGetData:null,fnSetData:null,mData:null,mRender:null,nTh:null,nTf:null,
sClass:null,sContentPadding:null,sDefaultContent:null,sName:null,sSortDataType:"std",sSortingClass:null,sSortingClassJUI:null,sTitle:null,sType:null,sWidth:null,sWidthOrig:null};n.defaults={aaData:null,aaSorting:[[0,"asc"]],aaSortingFixed:[],ajax:null,aLengthMenu:[10,25,50,100],aoColumns:null,aoColumnDefs:null,aoSearchCols:[],asStripeClasses:null,bAutoWidth:!0,bDeferRender:!1,bDestroy:!1,bFilter:!0,bInfo:!0,bLengthChange:!0,bPaginate:!0,bProcessing:!1,bRetrieve:!1,bScrollCollapse:!1,bServerSide:!1,
bSort:!0,bSortMulti:!0,bSortCellsTop:!1,bSortClasses:!0,bStateSave:!1,fnCreatedRow:null,fnDrawCallback:null,fnFooterCallback:null,fnFormatNumber:function(a){return a.toString().replace(/\B(?=(\d{3})+(?!\d))/g,this.oLanguage.sThousands)},fnHeaderCallback:null,fnInfoCallback:null,fnInitComplete:null,fnPreDrawCallback:null,fnRowCallback:null,fnServerData:null,fnServerParams:null,fnStateLoadCallback:function(a){try{return JSON.parse((-1===a.iStateDuration?sessionStorage:localStorage).getItem("DataTables_"+
a.sInstance+"_"+location.pathname))}catch(b){}},fnStateLoadParams:null,fnStateLoaded:null,fnStateSaveCallback:function(a,b){try{(-1===a.iStateDuration?sessionStorage:localStorage).setItem("DataTables_"+a.sInstance+"_"+location.pathname,JSON.stringify(b))}catch(c){}},fnStateSaveParams:null,iStateDuration:7200,iDeferLoading:null,iDisplayLength:10,iDisplayStart:0,iTabIndex:0,oClasses:{},oLanguage:{oAria:{sSortAscending:": activate to sort column ascending",sSortDescending:": activate to sort column descending"},
oPaginate:{sFirst:"First",sLast:"Last",sNext:"Next",sPrevious:"Previous"},sEmptyTable:"No data available in table",sInfo:"Showing _START_ to _END_ of _TOTAL_ entries",sInfoEmpty:"Showing 0 to 0 of 0 entries",sInfoFiltered:"(filtered from _MAX_ total entries)",sInfoPostFix:"",sDecimal:"",sThousands:",",sLengthMenu:"Show _MENU_ entries",sLoadingRecords:"Loading...",sProcessing:"Processing...",sSearch:"Search:",sSearchPlaceholder:"",sUrl:"",sZeroRecords:"No matching records found"},oSearch:h.extend({},
n.models.oSearch),sAjaxDataProp:"data",sAjaxSource:null,sDom:"lfrtip",searchDelay:null,sPaginationType:"simple_numbers",sScrollX:"",sScrollXInner:"",sScrollY:"",sServerMethod:"GET",renderer:null,rowId:"DT_RowId"};Z(n.defaults);n.defaults.column={aDataSort:null,iDataSort:-1,asSorting:["asc","desc"],bSearchable:!0,bSortable:!0,bVisible:!0,fnCreatedCell:null,mData:null,mRender:null,sCellType:"td",sClass:"",sContentPadding:"",sDefaultContent:null,sName:"",sSortDataType:"std",sTitle:null,sType:null,sWidth:null};
Z(n.defaults.column);n.models.oSettings={oFeatures:{bAutoWidth:null,bDeferRender:null,bFilter:null,bInfo:null,bLengthChange:null,bPaginate:null,bProcessing:null,bServerSide:null,bSort:null,bSortMulti:null,bSortClasses:null,bStateSave:null},oScroll:{bCollapse:null,iBarWidth:0,sX:null,sXInner:null,sY:null},oLanguage:{fnInfoCallback:null},oBrowser:{bScrollOversize:!1,bScrollbarLeft:!1,bBounding:!1,barWidth:0},ajax:null,aanFeatures:[],aoData:[],aiDisplay:[],aiDisplayMaster:[],aIds:{},aoColumns:[],aoHeader:[],
aoFooter:[],oPreviousSearch:{},aoPreSearchCols:[],aaSorting:null,aaSortingFixed:[],asStripeClasses:null,asDestroyStripes:[],sDestroyWidth:0,aoRowCallback:[],aoHeaderCallback:[],aoFooterCallback:[],aoDrawCallback:[],aoRowCreatedCallback:[],aoPreDrawCallback:[],aoInitComplete:[],aoStateSaveParams:[],aoStateLoadParams:[],aoStateLoaded:[],sTableId:"",nTable:null,nTHead:null,nTFoot:null,nTBody:null,nTableWrapper:null,bDeferLoading:!1,bInitialised:!1,aoOpenRows:[],sDom:null,searchDelay:null,sPaginationType:"two_button",
iStateDuration:0,aoStateSave:[],aoStateLoad:[],oSavedState:null,oLoadedState:null,sAjaxSource:null,sAjaxDataProp:null,bAjaxDataGet:!0,jqXHR:null,json:k,oAjaxData:k,fnServerData:null,aoServerParams:[],sServerMethod:null,fnFormatNumber:null,aLengthMenu:null,iDraw:0,bDrawing:!1,iDrawError:-1,_iDisplayLength:10,_iDisplayStart:0,_iRecordsTotal:0,_iRecordsDisplay:0,oClasses:{},bFiltered:!1,bSorted:!1,bSortCellsTop:null,oInit:null,aoDestroyCallback:[],fnRecordsTotal:function(){return"ssp"==y(this)?1*this._iRecordsTotal:
this.aiDisplayMaster.length},fnRecordsDisplay:function(){return"ssp"==y(this)?1*this._iRecordsDisplay:this.aiDisplay.length},fnDisplayEnd:function(){var a=this._iDisplayLength,b=this._iDisplayStart,c=b+a,d=this.aiDisplay.length,e=this.oFeatures,f=e.bPaginate;return e.bServerSide?!1===f||-1===a?b+d:Math.min(b+a,this._iRecordsDisplay):!f||c>d||-1===a?d:c},oInstance:null,sInstance:null,iTabIndex:0,nScrollHead:null,nScrollFoot:null,aLastSort:[],oPlugins:{},rowIdFn:null,rowId:null};n.ext=x={buttons:{},
classes:{},build:"dt/dt-1.10.18/cr-1.5.0/fc-3.2.5/fh-3.1.4/kt-2.5.0/r-2.2.2/rr-1.2.4",errMode:"alert",feature:[],search:[],selector:{cell:[],column:[],row:[]},internal:{},legacy:{ajax:null},pager:{},renderer:{pageButton:{},header:{}},order:{},type:{detect:[],search:{},order:{}},_unique:0,fnVersionCheck:n.fnVersionCheck,iApiIndex:0,oJUIClasses:{},sVersion:n.version};h.extend(x,{afnFiltering:x.search,aTypes:x.type.detect,ofnSearch:x.type.search,oSort:x.type.order,afnSortData:x.order,aoFeatures:x.feature,oApi:x.internal,oStdClasses:x.classes,oPagination:x.pager});
h.extend(n.ext.classes,{sTable:"dataTable",sNoFooter:"no-footer",sPageButton:"paginate_button",sPageButtonActive:"current",sPageButtonDisabled:"disabled",sStripeOdd:"odd",sStripeEven:"even",sRowEmpty:"dataTables_empty",sWrapper:"dataTables_wrapper",sFilter:"dataTables_filter",sInfo:"dataTables_info",sPaging:"dataTables_paginate paging_",sLength:"dataTables_length",sProcessing:"dataTables_processing",sSortAsc:"sorting_asc",sSortDesc:"sorting_desc",sSortable:"sorting",sSortableAsc:"sorting_asc_disabled",
sSortableDesc:"sorting_desc_disabled",sSortableNone:"sorting_disabled",sSortColumn:"sorting_",sFilterInput:"",sLengthSelect:"",sScrollWrapper:"dataTables_scroll",sScrollHead:"dataTables_scrollHead",sScrollHeadInner:"dataTables_scrollHeadInner",sScrollBody:"dataTables_scrollBody",sScrollFoot:"dataTables_scrollFoot",sScrollFootInner:"dataTables_scrollFootInner",sHeaderTH:"",sFooterTH:"",sSortJUIAsc:"",sSortJUIDesc:"",sSortJUI:"",sSortJUIAscAllowed:"",sSortJUIDescAllowed:"",sSortJUIWrapper:"",sSortIcon:"",
sJUIHeader:"",sJUIFooter:""});var Kb=n.ext.pager;h.extend(Kb,{simple:function(){return["previous","next"]},full:function(){return["first","previous","next","last"]},numbers:function(a,b){return[ia(a,b)]},simple_numbers:function(a,b){return["previous",ia(a,b),"next"]},full_numbers:function(a,b){return["first","previous",ia(a,b),"next","last"]},first_last_numbers:function(a,b){return["first",ia(a,b),"last"]},_numbers:ia,numbers_length:7});h.extend(!0,n.ext.renderer,{pageButton:{_:function(a,b,c,d,e,
f){var g=a.oClasses,j=a.oLanguage.oPaginate,i=a.oLanguage.oAria.paginate||{},m,l,n=0,o=function(b,d){var k,s,u,r,v=function(b){Ta(a,b.data.action,true)};k=0;for(s=d.length;k<s;k++){r=d[k];if(h.isArray(r)){u=h("<"+(r.DT_el||"div")+"/>").appendTo(b);o(u,r)}else{m=null;l="";switch(r){case "ellipsis":b.append('<span class="ellipsis">&#x2026;</span>');break;case "first":m=j.sFirst;l=r+(e>0?"":" "+g.sPageButtonDisabled);break;case "previous":m=j.sPrevious;l=r+(e>0?"":" "+g.sPageButtonDisabled);break;case "next":m=
j.sNext;l=r+(e<f-1?"":" "+g.sPageButtonDisabled);break;case "last":m=j.sLast;l=r+(e<f-1?"":" "+g.sPageButtonDisabled);break;default:m=r+1;l=e===r?g.sPageButtonActive:""}if(m!==null){u=h("<a>",{"class":g.sPageButton+" "+l,"aria-controls":a.sTableId,"aria-label":i[r],"data-dt-idx":n,tabindex:a.iTabIndex,id:c===0&&typeof r==="string"?a.sTableId+"_"+r:null}).html(m).appendTo(b);Wa(u,{action:r},v);n++}}}},s;try{s=h(b).find(H.activeElement).data("dt-idx")}catch(u){}o(h(b).empty(),d);s!==k&&h(b).find("[data-dt-idx="+
s+"]").focus()}}});h.extend(n.ext.type.detect,[function(a,b){var c=b.oLanguage.sDecimal;return $a(a,c)?"num"+c:null},function(a){if(a&&!(a instanceof Date)&&!Zb.test(a))return null;var b=Date.parse(a);return null!==b&&!isNaN(b)||M(a)?"date":null},function(a,b){var c=b.oLanguage.sDecimal;return $a(a,c,!0)?"num-fmt"+c:null},function(a,b){var c=b.oLanguage.sDecimal;return Pb(a,c)?"html-num"+c:null},function(a,b){var c=b.oLanguage.sDecimal;return Pb(a,c,!0)?"html-num-fmt"+c:null},function(a){return M(a)||
"string"===typeof a&&-1!==a.indexOf("<")?"html":null}]);h.extend(n.ext.type.search,{html:function(a){return M(a)?a:"string"===typeof a?a.replace(Mb," ").replace(Aa,""):""},string:function(a){return M(a)?a:"string"===typeof a?a.replace(Mb," "):a}});var za=function(a,b,c,d){if(0!==a&&(!a||"-"===a))return-Infinity;b&&(a=Ob(a,b));a.replace&&(c&&(a=a.replace(c,"")),d&&(a=a.replace(d,"")));return 1*a};h.extend(x.type.order,{"date-pre":function(a){a=Date.parse(a);return isNaN(a)?-Infinity:a},"html-pre":function(a){return M(a)?
"":a.replace?a.replace(/<.*?>/g,"").toLowerCase():a+""},"string-pre":function(a){return M(a)?"":"string"===typeof a?a.toLowerCase():!a.toString?"":a.toString()},"string-asc":function(a,b){return a<b?-1:a>b?1:0},"string-desc":function(a,b){return a<b?1:a>b?-1:0}});Da("");h.extend(!0,n.ext.renderer,{header:{_:function(a,b,c,d){h(a.nTable).on("order.dt.DT",function(e,f,g,h){if(a===f){e=c.idx;b.removeClass(c.sSortingClass+" "+d.sSortAsc+" "+d.sSortDesc).addClass(h[e]=="asc"?d.sSortAsc:h[e]=="desc"?d.sSortDesc:
c.sSortingClass)}})},jqueryui:function(a,b,c,d){h("<div/>").addClass(d.sSortJUIWrapper).append(b.contents()).append(h("<span/>").addClass(d.sSortIcon+" "+c.sSortingClassJUI)).appendTo(b);h(a.nTable).on("order.dt.DT",function(e,f,g,h){if(a===f){e=c.idx;b.removeClass(d.sSortAsc+" "+d.sSortDesc).addClass(h[e]=="asc"?d.sSortAsc:h[e]=="desc"?d.sSortDesc:c.sSortingClass);b.find("span."+d.sSortIcon).removeClass(d.sSortJUIAsc+" "+d.sSortJUIDesc+" "+d.sSortJUI+" "+d.sSortJUIAscAllowed+" "+d.sSortJUIDescAllowed).addClass(h[e]==
"asc"?d.sSortJUIAsc:h[e]=="desc"?d.sSortJUIDesc:c.sSortingClassJUI)}})}}});var Vb=function(a){return"string"===typeof a?a.replace(/</g,"&lt;").replace(/>/g,"&gt;").replace(/"/g,"&quot;"):a};n.render={number:function(a,b,c,d,e){return{display:function(f){if("number"!==typeof f&&"string"!==typeof f)return f;var g=0>f?"-":"",h=parseFloat(f);if(isNaN(h))return Vb(f);h=h.toFixed(c);f=Math.abs(h);h=parseInt(f,10);f=c?b+(f-h).toFixed(c).substring(2):"";return g+(d||"")+h.toString().replace(/\B(?=(\d{3})+(?!\d))/g,
a)+f+(e||"")}}},text:function(){return{display:Vb}}};h.extend(n.ext.internal,{_fnExternApiFunc:Lb,_fnBuildAjax:sa,_fnAjaxUpdate:lb,_fnAjaxParameters:ub,_fnAjaxUpdateDraw:vb,_fnAjaxDataSrc:ta,_fnAddColumn:Ea,_fnColumnOptions:ka,_fnAdjustColumnSizing:$,_fnVisibleToColumnIndex:aa,_fnColumnIndexToVisible:ba,_fnVisbleColumns:V,_fnGetColumns:ma,_fnColumnTypes:Ga,_fnApplyColumnDefs:ib,_fnHungarianMap:Z,_fnCamelToHungarian:J,_fnLanguageCompat:Ca,_fnBrowserDetect:gb,_fnAddData:O,_fnAddTr:na,_fnNodeToDataIndex:function(a,
b){return b._DT_RowIndex!==k?b._DT_RowIndex:null},_fnNodeToColumnIndex:function(a,b,c){return h.inArray(c,a.aoData[b].anCells)},_fnGetCellData:B,_fnSetCellData:jb,_fnSplitObjNotation:Ja,_fnGetObjectDataFn:S,_fnSetObjectDataFn:N,_fnGetDataMaster:Ka,_fnClearTable:oa,_fnDeleteIndex:pa,_fnInvalidate:da,_fnGetRowElements:Ia,_fnCreateTr:Ha,_fnBuildHead:kb,_fnDrawHead:fa,_fnDraw:P,_fnReDraw:T,_fnAddOptionsHtml:nb,_fnDetectHeader:ea,_fnGetUniqueThs:ra,_fnFeatureHtmlFilter:pb,_fnFilterComplete:ga,_fnFilterCustom:yb,
_fnFilterColumn:xb,_fnFilter:wb,_fnFilterCreateSearch:Pa,_fnEscapeRegex:Qa,_fnFilterData:zb,_fnFeatureHtmlInfo:sb,_fnUpdateInfo:Cb,_fnInfoMacros:Db,_fnInitialise:ha,_fnInitComplete:ua,_fnLengthChange:Ra,_fnFeatureHtmlLength:ob,_fnFeatureHtmlPaginate:tb,_fnPageChange:Ta,_fnFeatureHtmlProcessing:qb,_fnProcessingDisplay:C,_fnFeatureHtmlTable:rb,_fnScrollDraw:la,_fnApplyToChildren:I,_fnCalculateColumnWidths:Fa,_fnThrottle:Oa,_fnConvertToWidth:Eb,_fnGetWidestNode:Fb,_fnGetMaxLenString:Gb,_fnStringToCss:v,
_fnSortFlatten:X,_fnSort:mb,_fnSortAria:Ib,_fnSortListener:Va,_fnSortAttachListener:Ma,_fnSortingClasses:wa,_fnSortData:Hb,_fnSaveState:xa,_fnLoadState:Jb,_fnSettingsFromNode:ya,_fnLog:K,_fnMap:F,_fnBindAction:Wa,_fnCallbackReg:z,_fnCallbackFire:r,_fnLengthOverflow:Sa,_fnRenderer:Na,_fnDataSource:y,_fnRowAttributes:La,_fnExtend:Xa,_fnCalculateEnd:function(){}});h.fn.dataTable=n;n.$=h;h.fn.dataTableSettings=n.settings;h.fn.dataTableExt=n.ext;h.fn.DataTable=function(a){return h(this).dataTable(a).api()};
h.each(n,function(a,b){h.fn.DataTable[a]=b});return h.fn.dataTable});


/*!
 ColReorder 1.5.0
 ©2010-2018 SpryMedia Ltd - datatables.net/license
*/
(function(e){"function"===typeof define&&define.amd?define(["jquery","datatables.net"],function(o){return e(o,window,document)}):"object"===typeof exports?module.exports=function(o,l){o||(o=window);if(!l||!l.fn.dataTable)l=require("datatables.net")(o,l).$;return e(l,o,o.document)}:e(jQuery,window,document)})(function(e,o,l,r){function q(a){for(var b=[],c=0,f=a.length;c<f;c++)b[a[c]]=c;return b}function p(a,b,c){b=a.splice(b,1)[0];a.splice(c,0,b)}function s(a,b,c){for(var f=[],e=0,d=a.childNodes.length;e<
d;e++)1==a.childNodes[e].nodeType&&f.push(a.childNodes[e]);b=f[b];null!==c?a.insertBefore(b,f[c]):a.appendChild(b)}var t=e.fn.dataTable;e.fn.dataTableExt.oApi.fnColReorder=function(a,b,c,f,g){var d,h,j,m,i,l=a.aoColumns.length,k;i=function(a,b,c){if(a[b]&&"function"!==typeof a[b]){var d=a[b].split("."),f=d.shift();isNaN(1*f)||(a[b]=c[1*f]+"."+d.join("."))}};if(b!=c)if(0>b||b>=l)this.oApi._fnLog(a,1,"ColReorder 'from' index is out of bounds: "+b);else if(0>c||c>=l)this.oApi._fnLog(a,1,"ColReorder 'to' index is out of bounds: "+
c);else{j=[];d=0;for(h=l;d<h;d++)j[d]=d;p(j,b,c);var n=q(j);d=0;for(h=a.aaSorting.length;d<h;d++)a.aaSorting[d][0]=n[a.aaSorting[d][0]];if(null!==a.aaSortingFixed){d=0;for(h=a.aaSortingFixed.length;d<h;d++)a.aaSortingFixed[d][0]=n[a.aaSortingFixed[d][0]]}d=0;for(h=l;d<h;d++){k=a.aoColumns[d];j=0;for(m=k.aDataSort.length;j<m;j++)k.aDataSort[j]=n[k.aDataSort[j]];k.idx=n[k.idx]}e.each(a.aLastSort,function(b,c){a.aLastSort[b].src=n[c.src]});d=0;for(h=l;d<h;d++)k=a.aoColumns[d],"number"==typeof k.mData?
k.mData=n[k.mData]:e.isPlainObject(k.mData)&&(i(k.mData,"_",n),i(k.mData,"filter",n),i(k.mData,"sort",n),i(k.mData,"type",n));if(a.aoColumns[b].bVisible){i=this.oApi._fnColumnIndexToVisible(a,b);m=null;for(d=c<b?c:c+1;null===m&&d<l;)m=this.oApi._fnColumnIndexToVisible(a,d),d++;j=a.nTHead.getElementsByTagName("tr");d=0;for(h=j.length;d<h;d++)s(j[d],i,m);if(null!==a.nTFoot){j=a.nTFoot.getElementsByTagName("tr");d=0;for(h=j.length;d<h;d++)s(j[d],i,m)}d=0;for(h=a.aoData.length;d<h;d++)null!==a.aoData[d].nTr&&
s(a.aoData[d].nTr,i,m)}p(a.aoColumns,b,c);d=0;for(h=l;d<h;d++)a.oApi._fnColumnOptions(a,d,{});p(a.aoPreSearchCols,b,c);d=0;for(h=a.aoData.length;d<h;d++){m=a.aoData[d];if(k=m.anCells){p(k,b,c);j=0;for(i=k.length;j<i;j++)k[j]&&k[j]._DT_CellIndex&&(k[j]._DT_CellIndex.column=j)}"dom"!==m.src&&e.isArray(m._aData)&&p(m._aData,b,c)}d=0;for(h=a.aoHeader.length;d<h;d++)p(a.aoHeader[d],b,c);if(null!==a.aoFooter){d=0;for(h=a.aoFooter.length;d<h;d++)p(a.aoFooter[d],b,c)}(g||g===r)&&e.fn.dataTable.Api(a).rows().invalidate();
d=0;for(h=l;d<h;d++)e(a.aoColumns[d].nTh).off(".DT"),this.oApi._fnSortAttachListener(a,a.aoColumns[d].nTh,d);e(a.oInstance).trigger("column-reorder.dt",[a,{from:b,to:c,mapping:n,drop:f,iFrom:b,iTo:c,aiInvertMapping:n}])}};var i=function(a,b){var c=(new e.fn.dataTable.Api(a)).settings()[0];if(c._colReorder)return c._colReorder;!0===b&&(b={});var f=e.fn.dataTable.camelToHungarian;f&&(f(i.defaults,i.defaults,!0),f(i.defaults,b||{}));this.s={dt:null,enable:null,init:e.extend(!0,{},i.defaults,b),fixed:0,
fixedRight:0,reorderCallback:null,mouse:{startX:-1,startY:-1,offsetX:-1,offsetY:-1,target:-1,targetIndex:-1,fromIndex:-1},aoTargets:[]};this.dom={drag:null,pointer:null};this.s.enable=this.s.init.bEnable;this.s.dt=c;this.s.dt._colReorder=this;this._fnConstruct();return this};e.extend(i.prototype,{fnEnable:function(a){if(!1===a)return fnDisable();this.s.enable=!0},fnDisable:function(){this.s.enable=!1},fnReset:function(){this._fnOrderColumns(this.fnOrder());return this},fnGetCurrentOrder:function(){return this.fnOrder()},
fnOrder:function(a,b){var c=[],f,g,d=this.s.dt.aoColumns;if(a===r){f=0;for(g=d.length;f<g;f++)c.push(d[f]._ColReorder_iOrigCol);return c}if(b){d=this.fnOrder();f=0;for(g=a.length;f<g;f++)c.push(e.inArray(a[f],d));a=c}this._fnOrderColumns(q(a));return this},fnTranspose:function(a,b){b||(b="toCurrent");var c=this.fnOrder(),f=this.s.dt.aoColumns;return"toCurrent"===b?!e.isArray(a)?e.inArray(a,c):e.map(a,function(a){return e.inArray(a,c)}):!e.isArray(a)?f[a]._ColReorder_iOrigCol:e.map(a,function(a){return f[a]._ColReorder_iOrigCol})},
_fnConstruct:function(){var a=this,b=this.s.dt.aoColumns.length,c=this.s.dt.nTable,f;this.s.init.iFixedColumns&&(this.s.fixed=this.s.init.iFixedColumns);this.s.init.iFixedColumnsLeft&&(this.s.fixed=this.s.init.iFixedColumnsLeft);this.s.fixedRight=this.s.init.iFixedColumnsRight?this.s.init.iFixedColumnsRight:0;this.s.init.fnReorderCallback&&(this.s.reorderCallback=this.s.init.fnReorderCallback);for(f=0;f<b;f++)f>this.s.fixed-1&&f<b-this.s.fixedRight&&this._fnMouseListener(f,this.s.dt.aoColumns[f].nTh),
this.s.dt.aoColumns[f]._ColReorder_iOrigCol=f;this.s.dt.oApi._fnCallbackReg(this.s.dt,"aoStateSaveParams",function(b,c){a._fnStateSave.call(a,c)},"ColReorder_State");var g=null;this.s.init.aiOrder&&(g=this.s.init.aiOrder.slice());this.s.dt.oLoadedState&&("undefined"!=typeof this.s.dt.oLoadedState.ColReorder&&this.s.dt.oLoadedState.ColReorder.length==this.s.dt.aoColumns.length)&&(g=this.s.dt.oLoadedState.ColReorder);if(g)if(a.s.dt._bInitComplete)b=q(g),a._fnOrderColumns.call(a,b);else{var d=!1;e(c).on("draw.dt.colReorder",
function(){if(!a.s.dt._bInitComplete&&!d){d=true;var b=q(g);a._fnOrderColumns.call(a,b)}})}else this._fnSetColumnIndexes();e(c).on("destroy.dt.colReorder",function(){e(c).off("destroy.dt.colReorder draw.dt.colReorder");e.each(a.s.dt.aoColumns,function(a,b){e(b.nTh).off(".ColReorder");e(b.nTh).removeAttr("data-column-index")});a.s.dt._colReorder=null;a.s=null})},_fnOrderColumns:function(a){var b=!1;if(a.length!=this.s.dt.aoColumns.length)this.s.dt.oInstance.oApi._fnLog(this.s.dt,1,"ColReorder - array reorder does not match known number of columns. Skipping.");
else{for(var c=0,f=a.length;c<f;c++){var g=e.inArray(c,a);c!=g&&(p(a,g,c),this.s.dt.oInstance.fnColReorder(g,c,!0,!1),b=!0)}this._fnSetColumnIndexes();b&&(e.fn.dataTable.Api(this.s.dt).rows().invalidate(),(""!==this.s.dt.oScroll.sX||""!==this.s.dt.oScroll.sY)&&this.s.dt.oInstance.fnAdjustColumnSizing(!1),this.s.dt.oInstance.oApi._fnSaveState(this.s.dt),null!==this.s.reorderCallback&&this.s.reorderCallback.call(this))}},_fnStateSave:function(a){var b,c,f,g=this.s.dt.aoColumns;a.ColReorder=[];if(a.aaSorting){for(b=
0;b<a.aaSorting.length;b++)a.aaSorting[b][0]=g[a.aaSorting[b][0]]._ColReorder_iOrigCol;var d=e.extend(!0,[],a.aoSearchCols);b=0;for(c=g.length;b<c;b++)f=g[b]._ColReorder_iOrigCol,a.aoSearchCols[f]=d[b],a.abVisCols[f]=g[b].bVisible,a.ColReorder.push(f)}else if(a.order){for(b=0;b<a.order.length;b++)a.order[b][0]=g[a.order[b][0]]._ColReorder_iOrigCol;d=e.extend(!0,[],a.columns);b=0;for(c=g.length;b<c;b++)f=g[b]._ColReorder_iOrigCol,a.columns[f]=d[b],a.ColReorder.push(f)}},_fnMouseListener:function(a,
b){var c=this;e(b).on("mousedown.ColReorder",function(a){c.s.enable&&c._fnMouseDown.call(c,a,b)}).on("touchstart.ColReorder",function(a){c.s.enable&&c._fnMouseDown.call(c,a,b)})},_fnMouseDown:function(a,b){var c=this,f=e(a.target).closest("th, td").offset(),g=parseInt(e(b).attr("data-column-index"),10);g!==r&&(this.s.mouse.startX=this._fnCursorPosition(a,"pageX"),this.s.mouse.startY=this._fnCursorPosition(a,"pageY"),this.s.mouse.offsetX=this._fnCursorPosition(a,"pageX")-f.left,this.s.mouse.offsetY=
this._fnCursorPosition(a,"pageY")-f.top,this.s.mouse.target=this.s.dt.aoColumns[g].nTh,this.s.mouse.targetIndex=g,this.s.mouse.fromIndex=g,this._fnRegions(),e(l).on("mousemove.ColReorder touchmove.ColReorder",function(a){c._fnMouseMove.call(c,a)}).on("mouseup.ColReorder touchend.ColReorder",function(a){c._fnMouseUp.call(c,a)}))},_fnMouseMove:function(a){if(null===this.dom.drag){if(5>Math.pow(Math.pow(this._fnCursorPosition(a,"pageX")-this.s.mouse.startX,2)+Math.pow(this._fnCursorPosition(a,"pageY")-
this.s.mouse.startY,2),0.5))return;this._fnCreateDragNode()}this.dom.drag.css({left:this._fnCursorPosition(a,"pageX")-this.s.mouse.offsetX,top:this._fnCursorPosition(a,"pageY")-this.s.mouse.offsetY});for(var b=!1,c=this.s.mouse.toIndex,f=1,e=this.s.aoTargets.length;f<e;f++)if(this._fnCursorPosition(a,"pageX")<this.s.aoTargets[f-1].x+(this.s.aoTargets[f].x-this.s.aoTargets[f-1].x)/2){this.dom.pointer.css("left",this.s.aoTargets[f-1].x);this.s.mouse.toIndex=this.s.aoTargets[f-1].to;b=!0;break}b||(this.dom.pointer.css("left",
this.s.aoTargets[this.s.aoTargets.length-1].x),this.s.mouse.toIndex=this.s.aoTargets[this.s.aoTargets.length-1].to);this.s.init.bRealtime&&c!==this.s.mouse.toIndex&&(this.s.dt.oInstance.fnColReorder(this.s.mouse.fromIndex,this.s.mouse.toIndex),this.s.mouse.fromIndex=this.s.mouse.toIndex,(""!==this.s.dt.oScroll.sX||""!==this.s.dt.oScroll.sY)&&this.s.dt.oInstance.fnAdjustColumnSizing(!1),this._fnRegions())},_fnMouseUp:function(){e(l).off(".ColReorder");null!==this.dom.drag&&(this.dom.drag.remove(),
this.dom.pointer.remove(),this.dom.drag=null,this.dom.pointer=null,this.s.dt.oInstance.fnColReorder(this.s.mouse.fromIndex,this.s.mouse.toIndex,!0),this._fnSetColumnIndexes(),(""!==this.s.dt.oScroll.sX||""!==this.s.dt.oScroll.sY)&&this.s.dt.oInstance.fnAdjustColumnSizing(!1),this.s.dt.oInstance.oApi._fnSaveState(this.s.dt),null!==this.s.reorderCallback&&this.s.reorderCallback.call(this))},_fnRegions:function(){var a=this.s.dt.aoColumns;this.s.aoTargets.splice(0,this.s.aoTargets.length);this.s.aoTargets.push({x:e(this.s.dt.nTable).offset().left,
to:0});for(var b=0,c=this.s.aoTargets[0].x,f=0,g=a.length;f<g;f++)f!=this.s.mouse.fromIndex&&b++,a[f].bVisible&&"none"!==a[f].nTh.style.display&&(c+=e(a[f].nTh).outerWidth(),this.s.aoTargets.push({x:c,to:b}));0!==this.s.fixedRight&&this.s.aoTargets.splice(this.s.aoTargets.length-this.s.fixedRight);0!==this.s.fixed&&this.s.aoTargets.splice(0,this.s.fixed)},_fnCreateDragNode:function(){var a=""!==this.s.dt.oScroll.sX||""!==this.s.dt.oScroll.sY,b=this.s.dt.aoColumns[this.s.mouse.targetIndex].nTh,c=b.parentNode,
f=c.parentNode,g=f.parentNode,d=e(b).clone();this.dom.drag=e(g.cloneNode(!1)).addClass("DTCR_clonedTable").append(e(f.cloneNode(!1)).append(e(c.cloneNode(!1)).append(d[0]))).css({position:"absolute",top:0,left:0,width:e(b).outerWidth(),height:e(b).outerHeight()}).appendTo("body");this.dom.pointer=e("<div></div>").addClass("DTCR_pointer").css({position:"absolute",top:a?e("div.dataTables_scroll",this.s.dt.nTableWrapper).offset().top:e(this.s.dt.nTable).offset().top,height:a?e("div.dataTables_scroll",
this.s.dt.nTableWrapper).height():e(this.s.dt.nTable).height()}).appendTo("body")},_fnSetColumnIndexes:function(){e.each(this.s.dt.aoColumns,function(a,b){e(b.nTh).attr("data-column-index",a)})},_fnCursorPosition:function(a,b){return-1!==a.type.indexOf("touch")?a.originalEvent.touches[0][b]:a[b]}});i.defaults={aiOrder:null,bEnable:!0,bRealtime:!0,iFixedColumnsLeft:0,iFixedColumnsRight:0,fnReorderCallback:null};i.version="1.5.0";e.fn.dataTable.ColReorder=i;e.fn.DataTable.ColReorder=i;"function"==typeof e.fn.dataTable&&
"function"==typeof e.fn.dataTableExt.fnVersionCheck&&e.fn.dataTableExt.fnVersionCheck("1.10.8")?e.fn.dataTableExt.aoFeatures.push({fnInit:function(a){var b=a.oInstance;a._colReorder?b.oApi._fnLog(a,1,"ColReorder attempted to initialise twice. Ignoring second"):(b=a.oInit,new i(a,b.colReorder||b.oColReorder||{}));return null},cFeature:"R",sFeature:"ColReorder"}):alert("Warning: ColReorder requires DataTables 1.10.8 or greater - www.datatables.net/download");e(l).on("preInit.dt.colReorder",function(a,
b){if("dt"===a.namespace){var c=b.oInit.colReorder,f=t.defaults.colReorder;if(c||f)f=e.extend({},c,f),!1!==c&&new i(b,f)}});e.fn.dataTable.Api.register("colReorder.reset()",function(){return this.iterator("table",function(a){a._colReorder.fnReset()})});e.fn.dataTable.Api.register("colReorder.order()",function(a,b){return a?this.iterator("table",function(c){c._colReorder.fnOrder(a,b)}):this.context.length?this.context[0]._colReorder.fnOrder():null});e.fn.dataTable.Api.register("colReorder.transpose()",
function(a,b){return this.context.length&&this.context[0]._colReorder?this.context[0]._colReorder.fnTranspose(a,b):a});e.fn.dataTable.Api.register("colReorder.move()",function(a,b,c,e){this.context.length&&this.context[0]._colReorder.s.dt.oInstance.fnColReorder(a,b,c,e);return this});e.fn.dataTable.Api.register("colReorder.enable()",function(a){return this.iterator("table",function(b){b._colReorder&&b._colReorder.fnEnable(a)})});e.fn.dataTable.Api.register("colReorder.disable()",function(){return this.iterator("table",
function(a){a._colReorder&&a._colReorder.fnDisable()})});return i});


/*!
 FixedColumns 3.2.5
 ©2010-2018 SpryMedia Ltd - datatables.net/license
*/
(function(d){"function"===typeof define&&define.amd?define(["jquery","datatables.net"],function(p){return d(p,window,document)}):"object"===typeof exports?module.exports=function(p,r){p||(p=window);if(!r||!r.fn.dataTable)r=require("datatables.net")(p,r).$;return d(r,p,p.document)}:d(jQuery,window,document)})(function(d,p,r,t){var s=d.fn.dataTable,u,m=function(a,b){var c=this;if(this instanceof m){if(b===t||!0===b)b={};var e=d.fn.dataTable.camelToHungarian;e&&(e(m.defaults,m.defaults,!0),e(m.defaults,
b));e=(new d.fn.dataTable.Api(a)).settings()[0];this.s={dt:e,iTableColumns:e.aoColumns.length,aiOuterWidths:[],aiInnerWidths:[],rtl:"rtl"===d(e.nTable).css("direction")};this.dom={scroller:null,header:null,body:null,footer:null,grid:{wrapper:null,dt:null,left:{wrapper:null,head:null,body:null,foot:null},right:{wrapper:null,head:null,body:null,foot:null}},clone:{left:{header:null,body:null,footer:null},right:{header:null,body:null,footer:null}}};if(e._oFixedColumns)throw"FixedColumns already initialised on this table";
e._oFixedColumns=this;e._bInitComplete?this._fnConstruct(b):e.oApi._fnCallbackReg(e,"aoInitComplete",function(){c._fnConstruct(b)},"FixedColumns")}else alert("FixedColumns warning: FixedColumns must be initialised with the 'new' keyword.")};d.extend(m.prototype,{fnUpdate:function(){this._fnDraw(!0)},fnRedrawLayout:function(){this._fnColCalc();this._fnGridLayout();this.fnUpdate()},fnRecalculateHeight:function(a){delete a._DTTC_iHeight;a.style.height="auto"},fnSetRowHeight:function(a,b){a.style.height=
b+"px"},fnGetPosition:function(a){var b=this.s.dt.oInstance;if(d(a).parents(".DTFC_Cloned").length){if("tr"===a.nodeName.toLowerCase())return a=d(a).index(),b.fnGetPosition(d("tr",this.s.dt.nTBody)[a]);var c=d(a).index(),a=d(a.parentNode).index();return[b.fnGetPosition(d("tr",this.s.dt.nTBody)[a]),c,b.oApi._fnVisibleToColumnIndex(this.s.dt,c)]}return b.fnGetPosition(a)},_fnConstruct:function(a){var b=this;if("function"!=typeof this.s.dt.oInstance.fnVersionCheck||!0!==this.s.dt.oInstance.fnVersionCheck("1.8.0"))alert("FixedColumns "+
m.VERSION+" required DataTables 1.8.0 or later. Please upgrade your DataTables installation");else if(""===this.s.dt.oScroll.sX)this.s.dt.oInstance.oApi._fnLog(this.s.dt,1,"FixedColumns is not needed (no x-scrolling in DataTables enabled), so no action will be taken. Use 'FixedHeader' for column fixing when scrolling is not enabled");else{this.s=d.extend(!0,this.s,m.defaults,a);a=this.s.dt.oClasses;this.dom.grid.dt=d(this.s.dt.nTable).parents("div."+a.sScrollWrapper)[0];this.dom.scroller=d("div."+
a.sScrollBody,this.dom.grid.dt)[0];this._fnColCalc();this._fnGridSetup();var c,e=!1;d(this.s.dt.nTableWrapper).on("mousedown.DTFC",function(a){0===a.button&&(e=!0,d(r).one("mouseup",function(){e=!1}))});d(this.dom.scroller).on("mouseover.DTFC touchstart.DTFC",function(){e||(c="main")}).on("scroll.DTFC",function(a){!c&&a.originalEvent&&(c="main");if("main"===c&&(0<b.s.iLeftColumns&&(b.dom.grid.left.liner.scrollTop=b.dom.scroller.scrollTop),0<b.s.iRightColumns))b.dom.grid.right.liner.scrollTop=b.dom.scroller.scrollTop});
var f="onwheel"in r.createElement("div")?"wheel.DTFC":"mousewheel.DTFC";if(0<b.s.iLeftColumns)d(b.dom.grid.left.liner).on("mouseover.DTFC touchstart.DTFC",function(){e||(c="left")}).on("scroll.DTFC",function(a){!c&&a.originalEvent&&(c="left");"left"===c&&(b.dom.scroller.scrollTop=b.dom.grid.left.liner.scrollTop,0<b.s.iRightColumns&&(b.dom.grid.right.liner.scrollTop=b.dom.grid.left.liner.scrollTop))}).on(f,function(a){b.dom.scroller.scrollLeft-="wheel"===a.type?-a.originalEvent.deltaX:a.originalEvent.wheelDeltaX});
if(0<b.s.iRightColumns)d(b.dom.grid.right.liner).on("mouseover.DTFC touchstart.DTFC",function(){e||(c="right")}).on("scroll.DTFC",function(a){!c&&a.originalEvent&&(c="right");"right"===c&&(b.dom.scroller.scrollTop=b.dom.grid.right.liner.scrollTop,0<b.s.iLeftColumns&&(b.dom.grid.left.liner.scrollTop=b.dom.grid.right.liner.scrollTop))}).on(f,function(a){b.dom.scroller.scrollLeft-="wheel"===a.type?-a.originalEvent.deltaX:a.originalEvent.wheelDeltaX});d(p).on("resize.DTFC",function(){b._fnGridLayout.call(b)});
var g=!0,h=d(this.s.dt.nTable);h.on("draw.dt.DTFC",function(){b._fnColCalc();b._fnDraw.call(b,g);g=!1}).on("column-sizing.dt.DTFC",function(){b._fnColCalc();b._fnGridLayout(b)}).on("column-visibility.dt.DTFC",function(a,c,d,e,f){if(f===t||f)b._fnColCalc(),b._fnGridLayout(b),b._fnDraw(!0)}).on("select.dt.DTFC deselect.dt.DTFC",function(a){"dt"===a.namespace&&b._fnDraw(!1)}).on("destroy.dt.DTFC",function(){h.off(".DTFC");d(b.dom.scroller).off(".DTFC");d(p).off(".DTFC");d(b.s.dt.nTableWrapper).off(".DTFC");
d(b.dom.grid.left.liner).off(".DTFC "+f);d(b.dom.grid.left.wrapper).remove();d(b.dom.grid.right.liner).off(".DTFC "+f);d(b.dom.grid.right.wrapper).remove()});this._fnGridLayout();this.s.dt.oInstance.fnDraw(!1)}},_fnColCalc:function(){var a=this,b=0,c=0;this.s.aiInnerWidths=[];this.s.aiOuterWidths=[];d.each(this.s.dt.aoColumns,function(e,f){var g=d(f.nTh),h;if(g.filter(":visible").length){var i=g.outerWidth();0===a.s.aiOuterWidths.length&&(h=d(a.s.dt.nTable).css("border-left-width"),i+="string"===
typeof h&&-1===h.indexOf("px")?1:parseInt(h,10));a.s.aiOuterWidths.length===a.s.dt.aoColumns.length-1&&(h=d(a.s.dt.nTable).css("border-right-width"),i+="string"===typeof h&&-1===h.indexOf("px")?1:parseInt(h,10));a.s.aiOuterWidths.push(i);a.s.aiInnerWidths.push(g.width());e<a.s.iLeftColumns&&(b+=i);a.s.iTableColumns-a.s.iRightColumns<=e&&(c+=i)}else a.s.aiInnerWidths.push(0),a.s.aiOuterWidths.push(0)});this.s.iLeftWidth=b;this.s.iRightWidth=c},_fnGridSetup:function(){var a=this._fnDTOverflow(),b;this.dom.body=
this.s.dt.nTable;this.dom.header=this.s.dt.nTHead.parentNode;this.dom.header.parentNode.parentNode.style.position="relative";var c=d('<div class="DTFC_ScrollWrapper" style="position:relative; clear:both;"><div class="DTFC_LeftWrapper" style="position:absolute; top:0; left:0;" aria-hidden="true"><div class="DTFC_LeftHeadWrapper" style="position:relative; top:0; left:0; overflow:hidden;"></div><div class="DTFC_LeftBodyWrapper" style="position:relative; top:0; left:0; overflow:hidden;"><div class="DTFC_LeftBodyLiner" style="position:relative; top:0; left:0; overflow-y:scroll;"></div></div><div class="DTFC_LeftFootWrapper" style="position:relative; top:0; left:0; overflow:hidden;"></div></div><div class="DTFC_RightWrapper" style="position:absolute; top:0; right:0;" aria-hidden="true"><div class="DTFC_RightHeadWrapper" style="position:relative; top:0; left:0;"><div class="DTFC_RightHeadBlocker DTFC_Blocker" style="position:absolute; top:0; bottom:0;"></div></div><div class="DTFC_RightBodyWrapper" style="position:relative; top:0; left:0; overflow:hidden;"><div class="DTFC_RightBodyLiner" style="position:relative; top:0; left:0; overflow-y:scroll;"></div></div><div class="DTFC_RightFootWrapper" style="position:relative; top:0; left:0;"><div class="DTFC_RightFootBlocker DTFC_Blocker" style="position:absolute; top:0; bottom:0;"></div></div></div></div>')[0],
e=c.childNodes[0],f=c.childNodes[1];this.dom.grid.dt.parentNode.insertBefore(c,this.dom.grid.dt);c.appendChild(this.dom.grid.dt);this.dom.grid.wrapper=c;0<this.s.iLeftColumns&&(this.dom.grid.left.wrapper=e,this.dom.grid.left.head=e.childNodes[0],this.dom.grid.left.body=e.childNodes[1],this.dom.grid.left.liner=d("div.DTFC_LeftBodyLiner",c)[0],c.appendChild(e));0<this.s.iRightColumns&&(this.dom.grid.right.wrapper=f,this.dom.grid.right.head=f.childNodes[0],this.dom.grid.right.body=f.childNodes[1],this.dom.grid.right.liner=
d("div.DTFC_RightBodyLiner",c)[0],f.style.right=a.bar+"px",b=d("div.DTFC_RightHeadBlocker",c)[0],b.style.width=a.bar+"px",b.style.right=-a.bar+"px",this.dom.grid.right.headBlock=b,b=d("div.DTFC_RightFootBlocker",c)[0],b.style.width=a.bar+"px",b.style.right=-a.bar+"px",this.dom.grid.right.footBlock=b,c.appendChild(f));if(this.s.dt.nTFoot&&(this.dom.footer=this.s.dt.nTFoot.parentNode,0<this.s.iLeftColumns&&(this.dom.grid.left.foot=e.childNodes[2]),0<this.s.iRightColumns))this.dom.grid.right.foot=f.childNodes[2];
this.s.rtl&&d("div.DTFC_RightHeadBlocker",c).css({left:-a.bar+"px",right:""})},_fnGridLayout:function(){var a=this,b=this.dom.grid;d(b.wrapper).width();var c=this.s.dt.nTable.parentNode.offsetHeight,e=this.s.dt.nTable.parentNode.parentNode.offsetHeight,f=this._fnDTOverflow(),g=this.s.iLeftWidth,h=this.s.iRightWidth,i="rtl"===d(this.dom.body).css("direction"),j=function(b,c){f.bar?a._firefoxScrollError()?34<d(b).height()&&(b.style.width=c+f.bar+"px"):b.style.width=c+f.bar+"px":(b.style.width=c+20+
"px",b.style.paddingRight="20px",b.style.boxSizing="border-box")};f.x&&(c-=f.bar);b.wrapper.style.height=e+"px";0<this.s.iLeftColumns&&(e=b.left.wrapper,e.style.width=g+"px",e.style.height="1px",i?(e.style.left="",e.style.right=0):(e.style.left=0,e.style.right=""),b.left.body.style.height=c+"px",b.left.foot&&(b.left.foot.style.top=(f.x?f.bar:0)+"px"),j(b.left.liner,g),b.left.liner.style.height=c+"px",b.left.liner.style.maxHeight=c+"px");0<this.s.iRightColumns&&(e=b.right.wrapper,e.style.width=h+"px",
e.style.height="1px",this.s.rtl?(e.style.left=f.y?f.bar+"px":0,e.style.right=""):(e.style.left="",e.style.right=f.y?f.bar+"px":0),b.right.body.style.height=c+"px",b.right.foot&&(b.right.foot.style.top=(f.x?f.bar:0)+"px"),j(b.right.liner,h),b.right.liner.style.height=c+"px",b.right.liner.style.maxHeight=c+"px",b.right.headBlock.style.display=f.y?"block":"none",b.right.footBlock.style.display=f.y?"block":"none")},_fnDTOverflow:function(){var a=this.s.dt.nTable,b=a.parentNode,c={x:!1,y:!1,bar:this.s.dt.oScroll.iBarWidth};
a.offsetWidth>b.clientWidth&&(c.x=!0);a.offsetHeight>b.clientHeight&&(c.y=!0);return c},_fnDraw:function(a){this._fnGridLayout();this._fnCloneLeft(a);this._fnCloneRight(a);null!==this.s.fnDrawCallback&&this.s.fnDrawCallback.call(this,this.dom.clone.left,this.dom.clone.right);d(this).trigger("draw.dtfc",{leftClone:this.dom.clone.left,rightClone:this.dom.clone.right})},_fnCloneRight:function(a){if(!(0>=this.s.iRightColumns)){var b,c=[];for(b=this.s.iTableColumns-this.s.iRightColumns;b<this.s.iTableColumns;b++)this.s.dt.aoColumns[b].bVisible&&
c.push(b);this._fnClone(this.dom.clone.right,this.dom.grid.right,c,a)}},_fnCloneLeft:function(a){if(!(0>=this.s.iLeftColumns)){var b,c=[];for(b=0;b<this.s.iLeftColumns;b++)this.s.dt.aoColumns[b].bVisible&&c.push(b);this._fnClone(this.dom.clone.left,this.dom.grid.left,c,a)}},_fnCopyLayout:function(a,b,c){for(var e=[],f=[],g=[],h=0,i=a.length;h<i;h++){var j=[];j.nTr=d(a[h].nTr).clone(c,!1)[0];for(var l=0,o=this.s.iTableColumns;l<o;l++)if(-1!==d.inArray(l,b)){var q=d.inArray(a[h][l].cell,g);-1===q?(q=
d(a[h][l].cell).clone(c,!1)[0],f.push(q),g.push(a[h][l].cell),j.push({cell:q,unique:a[h][l].unique})):j.push({cell:f[q],unique:a[h][l].unique})}e.push(j)}return e},_fnClone:function(a,b,c,e){var f=this,g,h,i,j,l,o,q,n,m,k=this.s.dt;if(e){d(a.header).remove();a.header=d(this.dom.header).clone(!0,!1)[0];a.header.className+=" DTFC_Cloned";a.header.style.width="100%";b.head.appendChild(a.header);n=this._fnCopyLayout(k.aoHeader,c,!0);j=d(">thead",a.header);j.empty();g=0;for(h=n.length;g<h;g++)j[0].appendChild(n[g].nTr);
k.oApi._fnDrawHead(k,n,!0)}else{n=this._fnCopyLayout(k.aoHeader,c,!1);m=[];k.oApi._fnDetectHeader(m,d(">thead",a.header)[0]);g=0;for(h=n.length;g<h;g++){i=0;for(j=n[g].length;i<j;i++)m[g][i].cell.className=n[g][i].cell.className,d("span.DataTables_sort_icon",m[g][i].cell).each(function(){this.className=d("span.DataTables_sort_icon",n[g][i].cell)[0].className})}}this._fnEqualiseHeights("thead",this.dom.header,a.header);"auto"==this.s.sHeightMatch&&d(">tbody>tr",f.dom.body).css("height","auto");null!==
a.body&&(d(a.body).remove(),a.body=null);a.body=d(this.dom.body).clone(!0)[0];a.body.className+=" DTFC_Cloned";a.body.style.paddingBottom=k.oScroll.iBarWidth+"px";a.body.style.marginBottom=2*k.oScroll.iBarWidth+"px";null!==a.body.getAttribute("id")&&a.body.removeAttribute("id");d(">thead>tr",a.body).empty();d(">tfoot",a.body).remove();var p=d("tbody",a.body)[0];d(p).empty();if(0<k.aiDisplay.length){h=d(">thead>tr",a.body)[0];for(q=0;q<c.length;q++)l=c[q],o=d(k.aoColumns[l].nTh).clone(!0)[0],o.innerHTML=
"",j=o.style,j.paddingTop="0",j.paddingBottom="0",j.borderTopWidth="0",j.borderBottomWidth="0",j.height=0,j.width=f.s.aiInnerWidths[l]+"px",h.appendChild(o);d(">tbody>tr",f.dom.body).each(function(a){var a=f.s.dt.oFeatures.bServerSide===false?f.s.dt.aiDisplay[f.s.dt._iDisplayStart+a]:a,b=f.s.dt.aoData[a].anCells||d(this).children("td, th"),e=this.cloneNode(false);e.removeAttribute("id");e.setAttribute("data-dt-row",a);for(q=0;q<c.length;q++){l=c[q];if(b.length>0){o=d(b[l]).clone(true,true)[0];o.removeAttribute("id");
o.setAttribute("data-dt-row",a);o.setAttribute("data-dt-column",l);e.appendChild(o)}}p.appendChild(e)})}else d(">tbody>tr",f.dom.body).each(function(){o=this.cloneNode(true);o.className=o.className+" DTFC_NoData";d("td",o).html("");p.appendChild(o)});a.body.style.width="100%";a.body.style.margin="0";a.body.style.padding="0";k.oScroller!==t&&(h=k.oScroller.dom.force,b.forcer?b.forcer.style.height=h.style.height:(b.forcer=h.cloneNode(!0),b.liner.appendChild(b.forcer)));b.liner.appendChild(a.body);this._fnEqualiseHeights("tbody",
f.dom.body,a.body);if(null!==k.nTFoot){if(e){null!==a.footer&&a.footer.parentNode.removeChild(a.footer);a.footer=d(this.dom.footer).clone(!0,!0)[0];a.footer.className+=" DTFC_Cloned";a.footer.style.width="100%";b.foot.appendChild(a.footer);n=this._fnCopyLayout(k.aoFooter,c,!0);b=d(">tfoot",a.footer);b.empty();g=0;for(h=n.length;g<h;g++)b[0].appendChild(n[g].nTr);k.oApi._fnDrawHead(k,n,!0)}else{n=this._fnCopyLayout(k.aoFooter,c,!1);b=[];k.oApi._fnDetectHeader(b,d(">tfoot",a.footer)[0]);g=0;for(h=n.length;g<
h;g++){i=0;for(j=n[g].length;i<j;i++)b[g][i].cell.className=n[g][i].cell.className}}this._fnEqualiseHeights("tfoot",this.dom.footer,a.footer)}b=k.oApi._fnGetUniqueThs(k,d(">thead",a.header)[0]);d(b).each(function(a){l=c[a];this.style.width=f.s.aiInnerWidths[l]+"px"});null!==f.s.dt.nTFoot&&(b=k.oApi._fnGetUniqueThs(k,d(">tfoot",a.footer)[0]),d(b).each(function(a){l=c[a];this.style.width=f.s.aiInnerWidths[l]+"px"}))},_fnGetTrNodes:function(a){for(var b=[],c=0,d=a.childNodes.length;c<d;c++)"TR"==a.childNodes[c].nodeName.toUpperCase()&&
b.push(a.childNodes[c]);return b},_fnEqualiseHeights:function(a,b,c){if(!("none"==this.s.sHeightMatch&&"thead"!==a&&"tfoot"!==a)){var e,f,g=b.getElementsByTagName(a)[0],c=c.getElementsByTagName(a)[0],a=d(">"+a+">tr:eq(0)",b).children(":first");a.outerHeight();a.height();for(var g=this._fnGetTrNodes(g),b=this._fnGetTrNodes(c),h=[],c=0,a=b.length;c<a;c++)e=g[c].offsetHeight,f=b[c].offsetHeight,e=f>e?f:e,"semiauto"==this.s.sHeightMatch&&(g[c]._DTTC_iHeight=e),h.push(e);c=0;for(a=b.length;c<a;c++)b[c].style.height=
h[c]+"px",g[c].style.height=h[c]+"px"}},_firefoxScrollError:function(){if(u===t){var a=d("<div/>").css({position:"absolute",top:0,left:0,height:10,width:50,overflow:"scroll"}).appendTo("body");u=a[0].clientWidth===a[0].offsetWidth&&0!==this._fnDTOverflow().bar;a.remove()}return u}});m.defaults={iLeftColumns:1,iRightColumns:0,fnDrawCallback:null,sHeightMatch:"semiauto"};m.version="3.2.5";s.Api.register("fixedColumns()",function(){return this});s.Api.register("fixedColumns().update()",function(){return this.iterator("table",
function(a){a._oFixedColumns&&a._oFixedColumns.fnUpdate()})});s.Api.register("fixedColumns().relayout()",function(){return this.iterator("table",function(a){a._oFixedColumns&&a._oFixedColumns.fnRedrawLayout()})});s.Api.register("rows().recalcHeight()",function(){return this.iterator("row",function(a,b){a._oFixedColumns&&a._oFixedColumns.fnRecalculateHeight(this.row(b).node())})});s.Api.register("fixedColumns().rowIndex()",function(a){a=d(a);return a.parents(".DTFC_Cloned").length?this.rows({page:"current"}).indexes()[a.index()]:
this.row(a).index()});s.Api.register("fixedColumns().cellIndex()",function(a){a=d(a);if(a.parents(".DTFC_Cloned").length){var b=a.parent().index(),b=this.rows({page:"current"}).indexes()[b],a=a.parents(".DTFC_LeftWrapper").length?a.index():this.columns().flatten().length-this.context[0]._oFixedColumns.s.iRightColumns+a.index();return{row:b,column:this.column.index("toData",a),columnVisible:a}}return this.cell(a).index()});d(r).on("init.dt.fixedColumns",function(a,b){if("dt"===a.namespace){var c=b.oInit.fixedColumns,
e=s.defaults.fixedColumns;if(c||e)e=d.extend({},c,e),!1!==c&&new m(b,e)}});d.fn.dataTable.FixedColumns=m;return d.fn.DataTable.FixedColumns=m});


/*!
 FixedHeader 3.1.4
 ©2009-2018 SpryMedia Ltd - datatables.net/license
*/
(function(d){"function"===typeof define&&define.amd?define(["jquery","datatables.net"],function(g){return d(g,window,document)}):"object"===typeof exports?module.exports=function(g,i){g||(g=window);if(!i||!i.fn.dataTable)i=require("datatables.net")(g,i).$;return d(i,g,g.document)}:d(jQuery,window,document)})(function(d,g,i,k){var j=d.fn.dataTable,l=0,h=function(a,b){if(!(this instanceof h))throw"FixedHeader must be initialised with the 'new' keyword.";!0===b&&(b={});a=new j.Api(a);this.c=d.extend(!0,
{},h.defaults,b);this.s={dt:a,position:{theadTop:0,tbodyTop:0,tfootTop:0,tfootBottom:0,width:0,left:0,tfootHeight:0,theadHeight:0,windowHeight:d(g).height(),visible:!0},headerMode:null,footerMode:null,autoWidth:a.settings()[0].oFeatures.bAutoWidth,namespace:".dtfc"+l++,scrollLeft:{header:-1,footer:-1},enable:!0};this.dom={floatingHeader:null,thead:d(a.table().header()),tbody:d(a.table().body()),tfoot:d(a.table().footer()),header:{host:null,floating:null,placeholder:null},footer:{host:null,floating:null,
placeholder:null}};this.dom.header.host=this.dom.thead.parent();this.dom.footer.host=this.dom.tfoot.parent();var e=a.settings()[0];if(e._fixedHeader)throw"FixedHeader already initialised on table "+e.nTable.id;e._fixedHeader=this;this._constructor()};d.extend(h.prototype,{enable:function(a){this.s.enable=a;this.c.header&&this._modeChange("in-place","header",!0);this.c.footer&&this.dom.tfoot.length&&this._modeChange("in-place","footer",!0);this.update()},headerOffset:function(a){a!==k&&(this.c.headerOffset=
a,this.update());return this.c.headerOffset},footerOffset:function(a){a!==k&&(this.c.footerOffset=a,this.update());return this.c.footerOffset},update:function(){this._positions();this._scroll(!0)},_constructor:function(){var a=this,b=this.s.dt;d(g).on("scroll"+this.s.namespace,function(){a._scroll()}).on("resize"+this.s.namespace,j.util.throttle(function(){a.s.position.windowHeight=d(g).height();a.update()},50));var e=d(".fh-fixedHeader");!this.c.headerOffset&&e.length&&(this.c.headerOffset=e.outerHeight());
e=d(".fh-fixedFooter");!this.c.footerOffset&&e.length&&(this.c.footerOffset=e.outerHeight());b.on("column-reorder.dt.dtfc column-visibility.dt.dtfc draw.dt.dtfc column-sizing.dt.dtfc responsive-display.dt.dtfc",function(){a.update()});b.on("destroy.dtfc",function(){a.c.header&&a._modeChange("in-place","header",true);a.c.footer&&a.dom.tfoot.length&&a._modeChange("in-place","footer",true);b.off(".dtfc");d(g).off(a.s.namespace)});this._positions();this._scroll()},_clone:function(a,b){var e=this.s.dt,
c=this.dom[a],f="header"===a?this.dom.thead:this.dom.tfoot;!b&&c.floating?c.floating.removeClass("fixedHeader-floating fixedHeader-locked"):(c.floating&&(c.placeholder.remove(),this._unsize(a),c.floating.children().detach(),c.floating.remove()),c.floating=d(e.table().node().cloneNode(!1)).css("table-layout","fixed").attr("aria-hidden","true").removeAttr("id").append(f).appendTo("body"),c.placeholder=f.clone(!1),c.placeholder.find("*[id]").removeAttr("id"),c.host.prepend(c.placeholder),this._matchWidths(c.placeholder,
c.floating))},_matchWidths:function(a,b){var e=function(b){return d(b,a).map(function(){return d(this).width()}).toArray()},c=function(a,c){d(a,b).each(function(a){d(this).css({width:c[a],minWidth:c[a]})})},f=e("th"),e=e("td");c("th",f);c("td",e)},_unsize:function(a){var b=this.dom[a].floating;b&&("footer"===a||"header"===a&&!this.s.autoWidth)?d("th, td",b).css({width:"",minWidth:""}):b&&"header"===a&&d("th, td",b).css("min-width","")},_horizontal:function(a,b){var e=this.dom[a],c=this.s.position,
d=this.s.scrollLeft;e.floating&&d[a]!==b&&(e.floating.css("left",c.left-b),d[a]=b)},_modeChange:function(a,b,e){var c=this.dom[b],f=this.s.position,g=this.dom["footer"===b?"tfoot":"thead"],h=d.contains(g[0],i.activeElement)?i.activeElement:null;h&&h.blur();if("in-place"===a){if(c.placeholder&&(c.placeholder.remove(),c.placeholder=null),this._unsize(b),"header"===b?c.host.prepend(g):c.host.append(g),c.floating)c.floating.remove(),c.floating=null}else"in"===a?(this._clone(b,e),c.floating.addClass("fixedHeader-floating").css("header"===
b?"top":"bottom",this.c[b+"Offset"]).css("left",f.left+"px").css("width",f.width+"px"),"footer"===b&&c.floating.css("top","")):"below"===a?(this._clone(b,e),c.floating.addClass("fixedHeader-locked").css("top",f.tfootTop-f.theadHeight).css("left",f.left+"px").css("width",f.width+"px")):"above"===a&&(this._clone(b,e),c.floating.addClass("fixedHeader-locked").css("top",f.tbodyTop).css("left",f.left+"px").css("width",f.width+"px"));h&&h!==i.activeElement&&setTimeout(function(){h.focus()},10);this.s.scrollLeft.header=
-1;this.s.scrollLeft.footer=-1;this.s[b+"Mode"]=a},_positions:function(){var a=this.s.dt.table(),b=this.s.position,e=this.dom,a=d(a.node()),c=a.children("thead"),f=a.children("tfoot"),e=e.tbody;b.visible=a.is(":visible");b.width=a.outerWidth();b.left=a.offset().left;b.theadTop=c.offset().top;b.tbodyTop=e.offset().top;b.theadHeight=b.tbodyTop-b.theadTop;f.length?(b.tfootTop=f.offset().top,b.tfootBottom=b.tfootTop+f.outerHeight(),b.tfootHeight=b.tfootBottom-b.tfootTop):(b.tfootTop=b.tbodyTop+e.outerHeight(),
b.tfootBottom=b.tfootTop,b.tfootHeight=b.tfootTop)},_scroll:function(a){var b=d(i).scrollTop(),e=d(i).scrollLeft(),c=this.s.position,f;if(this.s.enable&&(this.c.header&&(f=!c.visible||b<=c.theadTop-this.c.headerOffset?"in-place":b<=c.tfootTop-c.theadHeight-this.c.headerOffset?"in":"below",(a||f!==this.s.headerMode)&&this._modeChange(f,"header",a),this._horizontal("header",e)),this.c.footer&&this.dom.tfoot.length))b=!c.visible||b+c.windowHeight>=c.tfootBottom+this.c.footerOffset?"in-place":c.windowHeight+
b>c.tbodyTop+c.tfootHeight+this.c.footerOffset?"in":"above",(a||b!==this.s.footerMode)&&this._modeChange(b,"footer",a),this._horizontal("footer",e)}});h.version="3.1.4";h.defaults={header:!0,footer:!1,headerOffset:0,footerOffset:0};d.fn.dataTable.FixedHeader=h;d.fn.DataTable.FixedHeader=h;d(i).on("init.dt.dtfh",function(a,b){if("dt"===a.namespace){var e=b.oInit.fixedHeader,c=j.defaults.fixedHeader;if((e||c)&&!b._fixedHeader)c=d.extend({},c,e),!1!==e&&new h(b,c)}});j.Api.register("fixedHeader()",function(){});
j.Api.register("fixedHeader.adjust()",function(){return this.iterator("table",function(a){(a=a._fixedHeader)&&a.update()})});j.Api.register("fixedHeader.enable()",function(a){return this.iterator("table",function(b){b=b._fixedHeader;a=a!==k?a:!0;b&&a!==b.s.enable&&b.enable(a)})});j.Api.register("fixedHeader.disable()",function(){return this.iterator("table",function(a){(a=a._fixedHeader)&&a.s.enable&&a.enable(!1)})});d.each(["header","footer"],function(a,b){j.Api.register("fixedHeader."+b+"Offset()",
function(a){var c=this.context;return a===k?c.length&&c[0]._fixedHeader?c[0]._fixedHeader[b+"Offset"]():k:this.iterator("table",function(c){if(c=c._fixedHeader)c[b+"Offset"](a)})})});return h});


/*!
   Copyright 2009-2018 SpryMedia Ltd.

 This source file is free software, available under the following license:
   MIT license - http://datatables.net/license/mit

 This source file is distributed in the hope that it will be useful, but
 WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY
 or FITNESS FOR A PARTICULAR PURPOSE. See the license files for details.

 For details please refer to: http://www.datatables.net
 KeyTable 2.5.0
 ©2009-2018 SpryMedia Ltd - datatables.net/license
*/
var $jscomp=$jscomp||{};$jscomp.scope={};$jscomp.ASSUME_ES5=!1;$jscomp.ASSUME_NO_NATIVE_MAP=!1;$jscomp.ASSUME_NO_NATIVE_SET=!1;$jscomp.defineProperty=$jscomp.ASSUME_ES5||"function"==typeof Object.defineProperties?Object.defineProperty:function(b,g,e){b!=Array.prototype&&b!=Object.prototype&&(b[g]=e.value)};$jscomp.getGlobal=function(b){return"undefined"!=typeof window&&window===b?b:"undefined"!=typeof global&&null!=global?global:b};$jscomp.global=$jscomp.getGlobal(this);$jscomp.SYMBOL_PREFIX="jscomp_symbol_";
$jscomp.initSymbol=function(){$jscomp.initSymbol=function(){};$jscomp.global.Symbol||($jscomp.global.Symbol=$jscomp.Symbol)};$jscomp.Symbol=function(){var b=0;return function(g){return $jscomp.SYMBOL_PREFIX+(g||"")+b++}}();
$jscomp.initSymbolIterator=function(){$jscomp.initSymbol();var b=$jscomp.global.Symbol.iterator;b||(b=$jscomp.global.Symbol.iterator=$jscomp.global.Symbol("iterator"));"function"!=typeof Array.prototype[b]&&$jscomp.defineProperty(Array.prototype,b,{configurable:!0,writable:!0,value:function(){return $jscomp.arrayIterator(this)}});$jscomp.initSymbolIterator=function(){}};
$jscomp.initSymbolAsyncIterator=function(){$jscomp.initSymbol();var b=$jscomp.global.Symbol.asyncIterator;b||(b=$jscomp.global.Symbol.asyncIterator=$jscomp.global.Symbol("asyncIterator"));$jscomp.initSymbolAsyncIterator=function(){}};$jscomp.arrayIterator=function(b){var g=0;return $jscomp.iteratorPrototype(function(){return g<b.length?{done:!1,value:b[g++]}:{done:!0}})};
$jscomp.iteratorPrototype=function(b){$jscomp.initSymbolIterator();b={next:b};b[$jscomp.global.Symbol.iterator]=function(){return this};return b};$jscomp.iteratorFromArray=function(b,g){$jscomp.initSymbolIterator();b instanceof String&&(b+="");var e=0,n={next:function(){if(e<b.length){var l=e++;return{value:g(l,b[l]),done:!1}}n.next=function(){return{done:!0,value:void 0}};return n.next()}};n[Symbol.iterator]=function(){return n};return n};
$jscomp.polyfill=function(b,g,e,n){if(g){e=$jscomp.global;b=b.split(".");for(n=0;n<b.length-1;n++){var l=b[n];l in e||(e[l]={});e=e[l]}b=b[b.length-1];n=e[b];g=g(n);g!=n&&null!=g&&$jscomp.defineProperty(e,b,{configurable:!0,writable:!0,value:g})}};$jscomp.polyfill("Array.prototype.keys",function(b){return b?b:function(){return $jscomp.iteratorFromArray(this,function(b){return b})}},"es6","es3");
(function(b){"function"===typeof define&&define.amd?define(["jquery","datatables.net"],function(g){return b(g,window,document)}):"object"===typeof exports?module.exports=function(g,e){g||(g=window);e&&e.fn.dataTable||(e=require("datatables.net")(g,e).$);return b(e,g,g.document)}:b(jQuery,window,document)})(function(b,g,e,n){var l=b.fn.dataTable,p=function(a,c){if(!l.versionCheck||!l.versionCheck("1.10.8"))throw"KeyTable requires DataTables 1.10.8 or newer";this.c=b.extend(!0,{},l.defaults.keyTable,
p.defaults,c);this.s={dt:new l.Api(a),enable:!0,focusDraw:!1,waitingForDraw:!1,lastFocus:null};this.dom={};a=this.s.dt.settings()[0];if(c=a.keytable)return c;a.keytable=this;this._constructor()};b.extend(p.prototype,{blur:function(){this._blur()},enable:function(a){this.s.enable=a},focus:function(a,c){this._focus(this.s.dt.cell(a,c))},focused:function(a){if(!this.s.lastFocus)return!1;var c=this.s.lastFocus.cell.index();return a.row===c.row&&a.column===c.column},_constructor:function(){this._tabInput();
var a=this,c=this.s.dt,d=b(c.table().node());"static"===d.css("position")&&d.css("position","relative");b(c.table().body()).on("click.keyTable","th, td",function(b){if(!1!==a.s.enable){var d=c.cell(this);d.any()&&a._focus(d,null,!1,b)}});b(e).on("keydown.keyTable",function(b){a._key(b)});if(this.c.blurable)b(e).on("mousedown.keyTable",function(d){b(d.target).parents(".dataTables_filter").length&&a._blur();b(d.target).parents().filter(c.table().container()).length||b(d.target).parents("div.DTE").length||
b(d.target).parents("div.editor-datetime").length||b(d.target).parents().filter(".DTFC_Cloned").length||a._blur()});if(this.c.editor){var k=this.c.editor;k.on("open.keyTableMain",function(b,c,d){"inline"!==c&&a.s.enable&&(a.enable(!1),k.one("close.keyTable",function(){a.enable(!0)}))});if(this.c.editOnFocus)c.on("key-focus.keyTable key-refocus.keyTable",function(b,c,d,f){a._editor(null,f,!0)});c.on("key.keyTable",function(b,c,d,f,k){a._editor(d,k,!1)});b(c.table().body()).on("dblclick.keyTable","th, td",
function(b){!1!==a.s.enable&&c.cell(this).any()&&a._editor(null,b,!0)})}if(c.settings()[0].oFeatures.bStateSave)c.on("stateSaveParams.keyTable",function(b,c,d){d.keyTable=a.s.lastFocus?a.s.lastFocus.cell.index():null});c.on("draw.keyTable",function(d){if(!a.s.focusDraw){var f=a.s.lastFocus;if(f&&f.node&&b(f.node).closest("body")===e.body){f=a.s.lastFocus.relative;var k=c.page.info(),m=f.row+k.start;0!==k.recordsDisplay&&(m>=k.recordsDisplay&&(m=k.recordsDisplay-1),a._focus(m,f.column,!0,d))}}});this.c.clipboard&&
this._clipboard();c.on("destroy.keyTable",function(){c.off(".keyTable");b(c.table().body()).off("click.keyTable","th, td");b(e).off("keydown.keyTable").off("click.keyTable").off("copy.keyTable").off("paste.keyTable")});var f=c.state.loaded();if(f&&f.keyTable)c.one("init",function(){var a=c.cell(f.keyTable);a.any()&&a.focus()});else this.c.focus&&c.cell(this.c.focus).focus()},_blur:function(){if(this.s.enable&&this.s.lastFocus){var a=this.s.lastFocus.cell;b(a.node()).removeClass(this.c.className);
this.s.lastFocus=null;this._updateFixedColumns(a.index().column);this._emitEvent("key-blur",[this.s.dt,a])}},_clipboard:function(){var a=this.s.dt,c=this;g.getSelection&&(b(e).on("copy.keyTable",function(a){a=a.originalEvent;var b=g.getSelection().toString(),d=c.s.lastFocus;!b&&d&&(a.clipboardData.setData("text/plain",d.cell.render(c.c.clipboardOrthogonal)),a.preventDefault())}),b(e).on("paste.keyTable",function(b){b=b.originalEvent;var d=c.s.lastFocus,f=e.activeElement,m=c.c.editor,h;!d||f&&"body"!==
f.nodeName.toLowerCase()||(b.preventDefault(),g.clipboardData&&g.clipboardData.getData?h=g.clipboardData.getData("Text"):b.clipboardData&&b.clipboardData.getData&&(h=b.clipboardData.getData("text/plain")),m?m.inline(d.cell.index()).set(m.displayed()[0],h).submit():(d.cell.data(h),a.draw(!1)))}))},_columns:function(){var a=this.s.dt,b=a.columns(this.c.columns).indexes(),d=[];a.columns(":visible").every(function(a){-1!==b.indexOf(a)&&d.push(a)});return d},_editor:function(a,c,d){var k=this,f=this.s.dt,
m=this.c.editor,h=this.s.lastFocus.cell;if(!(b("div.DTE",h.node()).length||null!==a&&(0<=a&&9>=a||11===a||12===a||14<=a&&31>=a||112<=a&&123>=a||127<=a&&159>=a))){c.stopPropagation();13===a&&c.preventDefault();var g=function(){m.one("open.keyTable",function(){m.off("cancelOpen.keyTable");d||b("div.DTE_Field_InputControl input, div.DTE_Field_InputControl textarea").select();f.keys.enable(d?"tab-only":"navigation-only");f.on("key-blur.editor",function(){m.displayed()&&m.submit()});d&&b(f.table().container()).addClass("dtk-focus-alt");
m.on("submitUnsuccessful.keyTable",function(){k._focus(h,null,!1)});m.one("close",function(){f.keys.enable(!0);f.off("key-blur.editor");m.off(".keyTable");b(f.table().container()).removeClass("dtk-focus-alt")})}).one("cancelOpen.keyTable",function(){m.off(".keyTable")}).inline(h.index())};13===a?(d=!0,b(e).one("keyup",function(){g()})):g()}},_emitEvent:function(a,c){this.s.dt.iterator("table",function(d,k){b(d.nTable).triggerHandler(a,c)})},_focus:function(a,c,d,k){var f=this,m=this.s.dt,h=m.page.info(),
q=this.s.lastFocus;k||(k=null);if(this.s.enable){if("number"!==typeof a){if(!a.any())return;var l=a.index();c=l.column;a=m.rows({filter:"applied",order:"applied"}).indexes().indexOf(l.row);if(0>a)return;h.serverSide&&(a+=h.start)}if(-1!==h.length&&(a<h.start||a>=h.start+h.length))this.s.focusDraw=!0,this.s.waitingForDraw=!0,m.one("draw",function(){f.s.focusDraw=!1;f.s.waitingForDraw=!1;f._focus(a,c,n,k)}).page(Math.floor(a/h.length)).draw(!1);else if(-1!==b.inArray(c,this._columns())){h.serverSide&&
(a-=h.start);h=m.cells(null,c,{search:"applied",order:"applied"}).flatten();h=m.cell(h[a]);if(q){if(q.node===h.node()){this._emitEvent("key-refocus",[this.s.dt,h,k||null]);return}this._blur()}q=b(h.node());q.addClass(this.c.className);this._updateFixedColumns(c);if(d===n||!0===d)this._scroll(b(g),b(e.body),q,"offset"),d=m.table().body().parentNode,d!==m.table().header().parentNode&&(d=b(d.parentNode),this._scroll(d,d,q,"position"));this.s.lastFocus={cell:h,node:h.node(),relative:{row:m.rows({page:"current"}).indexes().indexOf(h.index().row),
column:h.index().column}};this._emitEvent("key-focus",[this.s.dt,h,k||null]);m.state.save()}}},_key:function(a){if(this.s.waitingForDraw)a.preventDefault();else{var c=this.s.enable,d=!0===c||"navigation-only"===c;if(c&&(!(0===a.keyCode||a.ctrlKey||a.metaKey||a.altKey)||a.ctrlKey&&a.altKey)&&this.s.lastFocus){var k=this.s.dt,f=this.s.dt.settings()[0].oScroll.sY?!0:!1;if(!this.c.keys||-1!==b.inArray(a.keyCode,this.c.keys))switch(a.keyCode){case 9:this._shift(a,a.shiftKey?"left":"right",!0);break;case 27:this.s.blurable&&
!0===c&&this._blur();break;case 33:case 34:d&&!f&&(a.preventDefault(),k.page(33===a.keyCode?"previous":"next").draw(!1));break;case 35:case 36:d&&(a.preventDefault(),c=k.cells({page:"current"}).indexes(),d=this._columns(),this._focus(k.cell(c[35===a.keyCode?c.length-1:d[0]]),null,!0,a));break;case 37:d&&this._shift(a,"left");break;case 38:d&&this._shift(a,"up");break;case 39:d&&this._shift(a,"right");break;case 40:d&&this._shift(a,"down");break;default:!0===c&&this._emitEvent("key",[k,a.keyCode,this.s.lastFocus.cell,
a])}}}},_scroll:function(a,b,d,k){var c=d[k](),m=d.outerHeight(),h=d.outerWidth(),g=b.scrollTop(),e=b.scrollLeft(),l=a.height();a=a.width();"position"===k&&(c.top+=parseInt(d.closest("table").css("top"),10));c.top<g&&b.scrollTop(c.top);c.left<e&&b.scrollLeft(c.left);c.top+m>g+l&&m<l&&b.scrollTop(c.top+m-l);c.left+h>e+a&&h<a&&b.scrollLeft(c.left+h-a)},_shift:function(a,c,d){var k=this.s.dt,f=k.page.info(),g=f.recordsDisplay,h=this.s.lastFocus.cell,e=this._columns();if(h){var l=k.rows({filter:"applied",
order:"applied"}).indexes().indexOf(h.index().row);f.serverSide&&(l+=f.start);k=k.columns(e).indexes().indexOf(h.index().column);f=e[k];"right"===c?k>=e.length-1?(l++,f=e[0]):f=e[k+1]:"left"===c?0===k?(l--,f=e[e.length-1]):f=e[k-1]:"up"===c?l--:"down"===c&&l++;0<=l&&l<g&&-1!==b.inArray(f,e)?(a.preventDefault(),this._focus(l,f,!0,a)):d&&this.c.blurable?this._blur():a.preventDefault()}},_tabInput:function(){var a=this,c=this.s.dt,d=null!==this.c.tabIndex?this.c.tabIndex:c.settings()[0].iTabIndex;if(-1!=
d)b('<div><input type="text" tabindex="'+d+'"/></div>').css({position:"absolute",height:1,width:0,overflow:"hidden"}).insertBefore(c.table().node()).children().on("focus",function(b){c.cell(":eq(0)",{page:"current"}).any()&&a._focus(c.cell(":eq(0)","0:visible",{page:"current"}),null,!0,b)})},_updateFixedColumns:function(a){var b=this.s.dt,d=b.settings()[0];if(d._oFixedColumns){var e=d.aoColumns.length-d._oFixedColumns.s.iRightColumns;(a<d._oFixedColumns.s.iLeftColumns||a>=e)&&b.fixedColumns().update()}}});
p.defaults={blurable:!0,className:"focus",clipboard:!0,clipboardOrthogonal:"display",columns:"",editor:null,editOnFocus:!1,focus:null,keys:null,tabIndex:null};p.version="2.5.0";b.fn.dataTable.KeyTable=p;b.fn.DataTable.KeyTable=p;l.Api.register("cell.blur()",function(){return this.iterator("table",function(a){a.keytable&&a.keytable.blur()})});l.Api.register("cell().focus()",function(){return this.iterator("cell",function(a,b,d){a.keytable&&a.keytable.focus(b,d)})});l.Api.register("keys.disable()",
function(){return this.iterator("table",function(a){a.keytable&&a.keytable.enable(!1)})});l.Api.register("keys.enable()",function(a){return this.iterator("table",function(b){b.keytable&&b.keytable.enable(a===n?!0:a)})});l.ext.selector.cell.push(function(a,b,d){b=b.focused;a=a.keytable;var c=[];if(!a||b===n)return d;for(var f=0,e=d.length;f<e;f++)(!0===b&&a.focused(d[f])||!1===b&&!a.focused(d[f]))&&c.push(d[f]);return c});b(e).on("preInit.dt.dtk",function(a,c,d){"dt"===a.namespace&&(a=c.oInit.keys,
d=l.defaults.keys,a||d)&&(d=b.extend({},d,a),!1!==a&&new p(c,d))});return p});


/*!
 Responsive 2.2.2
 2014-2018 SpryMedia Ltd - datatables.net/license
*/
(function(d){"function"===typeof define&&define.amd?define(["jquery","datatables.net"],function(l){return d(l,window,document)}):"object"===typeof exports?module.exports=function(l,j){l||(l=window);if(!j||!j.fn.dataTable)j=require("datatables.net")(l,j).$;return d(j,l,l.document)}:d(jQuery,window,document)})(function(d,l,j,q){function t(a,b,c){var e=b+"-"+c;if(n[e])return n[e];for(var d=[],a=a.cell(b,c).node().childNodes,b=0,c=a.length;b<c;b++)d.push(a[b]);return n[e]=d}function r(a,b,d){var e=b+
"-"+d;if(n[e]){for(var a=a.cell(b,d).node(),d=n[e][0].parentNode.childNodes,b=[],f=0,g=d.length;f<g;f++)b.push(d[f]);d=0;for(f=b.length;d<f;d++)a.appendChild(b[d]);n[e]=q}}var o=d.fn.dataTable,i=function(a,b){if(!o.versionCheck||!o.versionCheck("1.10.10"))throw"DataTables Responsive requires DataTables 1.10.10 or newer";this.s={dt:new o.Api(a),columns:[],current:[]};this.s.dt.settings()[0].responsive||(b&&"string"===typeof b.details?b.details={type:b.details}:b&&!1===b.details?b.details={type:!1}:
b&&!0===b.details&&(b.details={type:"inline"}),this.c=d.extend(!0,{},i.defaults,o.defaults.responsive,b),a.responsive=this,this._constructor())};d.extend(i.prototype,{_constructor:function(){var a=this,b=this.s.dt,c=b.settings()[0],e=d(l).width();b.settings()[0]._responsive=this;d(l).on("resize.dtr orientationchange.dtr",o.util.throttle(function(){var b=d(l).width();b!==e&&(a._resize(),e=b)}));c.oApi._fnCallbackReg(c,"aoRowCreatedCallback",function(e){-1!==d.inArray(!1,a.s.current)&&d(">td, >th",
e).each(function(e){e=b.column.index("toData",e);!1===a.s.current[e]&&d(this).css("display","none")})});b.on("destroy.dtr",function(){b.off(".dtr");d(b.table().body()).off(".dtr");d(l).off("resize.dtr orientationchange.dtr");d.each(a.s.current,function(b,e){!1===e&&a._setColumnVis(b,!0)})});this.c.breakpoints.sort(function(a,b){return a.width<b.width?1:a.width>b.width?-1:0});this._classLogic();this._resizeAuto();c=this.c.details;!1!==c.type&&(a._detailsInit(),b.on("column-visibility.dtr",function(){a._timer&&
clearTimeout(a._timer);a._timer=setTimeout(function(){a._timer=null;a._classLogic();a._resizeAuto();a._resize();a._redrawChildren()},100)}),b.on("draw.dtr",function(){a._redrawChildren()}),d(b.table().node()).addClass("dtr-"+c.type));b.on("column-reorder.dtr",function(){a._classLogic();a._resizeAuto();a._resize()});b.on("column-sizing.dtr",function(){a._resizeAuto();a._resize()});b.on("preXhr.dtr",function(){var e=[];b.rows().every(function(){this.child.isShown()&&e.push(this.id(true))});b.one("draw.dtr",
function(){a._resizeAuto();a._resize();b.rows(e).every(function(){a._detailsDisplay(this,false)})})});b.on("init.dtr",function(){a._resizeAuto();a._resize();d.inArray(false,a.s.current)&&b.columns.adjust()});this._resize()},_columnsVisiblity:function(a){var b=this.s.dt,c=this.s.columns,e,f,g=c.map(function(a,b){return{columnIdx:b,priority:a.priority}}).sort(function(a,b){return a.priority!==b.priority?a.priority-b.priority:a.columnIdx-b.columnIdx}),h=d.map(c,function(e,c){return!1===b.column(c).visible()?
"not-visible":e.auto&&null===e.minWidth?!1:!0===e.auto?"-":-1!==d.inArray(a,e.includeIn)}),m=0;e=0;for(f=h.length;e<f;e++)!0===h[e]&&(m+=c[e].minWidth);e=b.settings()[0].oScroll;e=e.sY||e.sX?e.iBarWidth:0;m=b.table().container().offsetWidth-e-m;e=0;for(f=h.length;e<f;e++)c[e].control&&(m-=c[e].minWidth);var s=!1;e=0;for(f=g.length;e<f;e++){var k=g[e].columnIdx;"-"===h[k]&&(!c[k].control&&c[k].minWidth)&&(s||0>m-c[k].minWidth?(s=!0,h[k]=!1):h[k]=!0,m-=c[k].minWidth)}g=!1;e=0;for(f=c.length;e<f;e++)if(!c[e].control&&
!c[e].never&&!1===h[e]){g=!0;break}e=0;for(f=c.length;e<f;e++)c[e].control&&(h[e]=g),"not-visible"===h[e]&&(h[e]=!1);-1===d.inArray(!0,h)&&(h[0]=!0);return h},_classLogic:function(){var a=this,b=this.c.breakpoints,c=this.s.dt,e=c.columns().eq(0).map(function(a){var b=this.column(a),e=b.header().className,a=c.settings()[0].aoColumns[a].responsivePriority;a===q&&(b=d(b.header()).data("priority"),a=b!==q?1*b:1E4);return{className:e,includeIn:[],auto:!1,control:!1,never:e.match(/\bnever\b/)?!0:!1,priority:a}}),
f=function(a,b){var c=e[a].includeIn;-1===d.inArray(b,c)&&c.push(b)},g=function(d,c,g,k){if(g)if("max-"===g){k=a._find(c).width;c=0;for(g=b.length;c<g;c++)b[c].width<=k&&f(d,b[c].name)}else if("min-"===g){k=a._find(c).width;c=0;for(g=b.length;c<g;c++)b[c].width>=k&&f(d,b[c].name)}else{if("not-"===g){c=0;for(g=b.length;c<g;c++)-1===b[c].name.indexOf(k)&&f(d,b[c].name)}}else e[d].includeIn.push(c)};e.each(function(a,e){for(var c=a.className.split(" "),f=!1,i=0,l=c.length;i<l;i++){var j=d.trim(c[i]);
if("all"===j){f=!0;a.includeIn=d.map(b,function(a){return a.name});return}if("none"===j||a.never){f=!0;return}if("control"===j){f=!0;a.control=!0;return}d.each(b,function(a,b){var d=b.name.split("-"),c=j.match(RegExp("(min\\-|max\\-|not\\-)?("+d[0]+")(\\-[_a-zA-Z0-9])?"));c&&(f=!0,c[2]===d[0]&&c[3]==="-"+d[1]?g(e,b.name,c[1],c[2]+c[3]):c[2]===d[0]&&!c[3]&&g(e,b.name,c[1],c[2]))})}f||(a.auto=!0)});this.s.columns=e},_detailsDisplay:function(a,b){var c=this,e=this.s.dt,f=this.c.details;if(f&&!1!==f.type){var g=
f.display(a,b,function(){return f.renderer(e,a[0],c._detailsObj(a[0]))});(!0===g||!1===g)&&d(e.table().node()).triggerHandler("responsive-display.dt",[e,a,g,b])}},_detailsInit:function(){var a=this,b=this.s.dt,c=this.c.details;"inline"===c.type&&(c.target="td:first-child, th:first-child");b.on("draw.dtr",function(){a._tabIndexes()});a._tabIndexes();d(b.table().body()).on("keyup.dtr","td, th",function(a){a.keyCode===13&&d(this).data("dtr-keyboard")&&d(this).click()});var e=c.target;d(b.table().body()).on("click.dtr mousedown.dtr mouseup.dtr",
"string"===typeof e?e:"td, th",function(c){if(d(b.table().node()).hasClass("collapsed")&&d.inArray(d(this).closest("tr").get(0),b.rows().nodes().toArray())!==-1){if(typeof e==="number"){var g=e<0?b.columns().eq(0).length+e:e;if(b.cell(this).index().column!==g)return}g=b.row(d(this).closest("tr"));c.type==="click"?a._detailsDisplay(g,false):c.type==="mousedown"?d(this).css("outline","none"):c.type==="mouseup"&&d(this).blur().css("outline","")}})},_detailsObj:function(a){var b=this,c=this.s.dt;return d.map(this.s.columns,
function(e,d){if(!e.never&&!e.control)return{title:c.settings()[0].aoColumns[d].sTitle,data:c.cell(a,d).render(b.c.orthogonal),hidden:c.column(d).visible()&&!b.s.current[d],columnIndex:d,rowIndex:a}})},_find:function(a){for(var b=this.c.breakpoints,c=0,e=b.length;c<e;c++)if(b[c].name===a)return b[c]},_redrawChildren:function(){var a=this,b=this.s.dt;b.rows({page:"current"}).iterator("row",function(c,e){b.row(e);a._detailsDisplay(b.row(e),!0)})},_resize:function(){var a=this,b=this.s.dt,c=d(l).width(),
e=this.c.breakpoints,f=e[0].name,g=this.s.columns,h,m=this.s.current.slice();for(h=e.length-1;0<=h;h--)if(c<=e[h].width){f=e[h].name;break}var i=this._columnsVisiblity(f);this.s.current=i;e=!1;h=0;for(c=g.length;h<c;h++)if(!1===i[h]&&!g[h].never&&!g[h].control&&!1===!b.column(h).visible()){e=!0;break}d(b.table().node()).toggleClass("collapsed",e);var k=!1,j=0;b.columns().eq(0).each(function(b,c){!0===i[c]&&j++;i[c]!==m[c]&&(k=!0,a._setColumnVis(b,i[c]))});k&&(this._redrawChildren(),d(b.table().node()).trigger("responsive-resize.dt",
[b,this.s.current]),0===b.page.info().recordsDisplay&&d("td",b.table().body()).eq(0).attr("colspan",j))},_resizeAuto:function(){var a=this.s.dt,b=this.s.columns;if(this.c.auto&&-1!==d.inArray(!0,d.map(b,function(a){return a.auto}))){d.isEmptyObject(n)||d.each(n,function(b){b=b.split("-");r(a,1*b[0],1*b[1])});a.table().node();var c=a.table().node().cloneNode(!1),e=d(a.table().header().cloneNode(!1)).appendTo(c),f=d(a.table().body()).clone(!1,!1).empty().appendTo(c),g=a.columns().header().filter(function(b){return a.column(b).visible()}).to$().clone(!1).css("display",
"table-cell").css("min-width",0);d(f).append(d(a.rows({page:"current"}).nodes()).clone(!1)).find("th, td").css("display","");if(f=a.table().footer()){var f=d(f.cloneNode(!1)).appendTo(c),h=a.columns().footer().filter(function(b){return a.column(b).visible()}).to$().clone(!1).css("display","table-cell");d("<tr/>").append(h).appendTo(f)}d("<tr/>").append(g).appendTo(e);"inline"===this.c.details.type&&d(c).addClass("dtr-inline collapsed");d(c).find("[name]").removeAttr("name");d(c).css("position","relative");
c=d("<div/>").css({width:1,height:1,overflow:"hidden",clear:"both"}).append(c);c.insertBefore(a.table().node());g.each(function(c){c=a.column.index("fromVisible",c);b[c].minWidth=this.offsetWidth||0});c.remove()}},_setColumnVis:function(a,b){var c=this.s.dt,e=b?"":"none";d(c.column(a).header()).css("display",e);d(c.column(a).footer()).css("display",e);c.column(a).nodes().to$().css("display",e);d.isEmptyObject(n)||c.cells(null,a).indexes().each(function(a){r(c,a.row,a.column)})},_tabIndexes:function(){var a=
this.s.dt,b=a.cells({page:"current"}).nodes().to$(),c=a.settings()[0],e=this.c.details.target;b.filter("[data-dtr-keyboard]").removeData("[data-dtr-keyboard]");"number"===typeof e?a.cells(null,e,{page:"current"}).nodes().to$().attr("tabIndex",c.iTabIndex).data("dtr-keyboard",1):("td:first-child, th:first-child"===e&&(e=">td:first-child, >th:first-child"),d(e,a.rows({page:"current"}).nodes()).attr("tabIndex",c.iTabIndex).data("dtr-keyboard",1))}});i.breakpoints=[{name:"desktop",width:Infinity},{name:"tablet-l",
width:1024},{name:"tablet-p",width:768},{name:"mobile-l",width:480},{name:"mobile-p",width:320}];i.display={childRow:function(a,b,c){if(b){if(d(a.node()).hasClass("parent"))return a.child(c(),"child").show(),!0}else{if(a.child.isShown())return a.child(!1),d(a.node()).removeClass("parent"),!1;a.child(c(),"child").show();d(a.node()).addClass("parent");return!0}},childRowImmediate:function(a,b,c){if(!b&&a.child.isShown()||!a.responsive.hasHidden())return a.child(!1),d(a.node()).removeClass("parent"),
!1;a.child(c(),"child").show();d(a.node()).addClass("parent");return!0},modal:function(a){return function(b,c,e){if(c)d("div.dtr-modal-content").empty().append(e());else{var f=function(){g.remove();d(j).off("keypress.dtr")},g=d('<div class="dtr-modal"/>').append(d('<div class="dtr-modal-display"/>').append(d('<div class="dtr-modal-content"/>').append(e())).append(d('<div class="dtr-modal-close">&times;</div>').click(function(){f()}))).append(d('<div class="dtr-modal-background"/>').click(function(){f()})).appendTo("body");
d(j).on("keyup.dtr",function(a){27===a.keyCode&&(a.stopPropagation(),f())})}a&&a.header&&d("div.dtr-modal-content").prepend("<h2>"+a.header(b)+"</h2>")}}};var n={};i.renderer={listHiddenNodes:function(){return function(a,b,c){var e=d('<ul data-dtr-index="'+b+'" class="dtr-details"/>'),f=!1;d.each(c,function(b,c){c.hidden&&(d('<li data-dtr-index="'+c.columnIndex+'" data-dt-row="'+c.rowIndex+'" data-dt-column="'+c.columnIndex+'"><span class="dtr-title">'+c.title+"</span> </li>").append(d('<span class="dtr-data"/>').append(t(a,
c.rowIndex,c.columnIndex))).appendTo(e),f=!0)});return f?e:!1}},listHidden:function(){return function(a,b,c){return(a=d.map(c,function(a){return a.hidden?'<li data-dtr-index="'+a.columnIndex+'" data-dt-row="'+a.rowIndex+'" data-dt-column="'+a.columnIndex+'"><span class="dtr-title">'+a.title+'</span> <span class="dtr-data">'+a.data+"</span></li>":""}).join(""))?d('<ul data-dtr-index="'+b+'" class="dtr-details"/>').append(a):!1}},tableAll:function(a){a=d.extend({tableClass:""},a);return function(b,
c,e){b=d.map(e,function(a){return'<tr data-dt-row="'+a.rowIndex+'" data-dt-column="'+a.columnIndex+'"><td>'+a.title+":</td> <td>"+a.data+"</td></tr>"}).join("");return d('<table class="'+a.tableClass+' dtr-details" width="100%"/>').append(b)}}};i.defaults={breakpoints:i.breakpoints,auto:!0,details:{display:i.display.childRow,renderer:i.renderer.listHidden(),target:0,type:"inline"},orthogonal:"display"};var p=d.fn.dataTable.Api;p.register("responsive()",function(){return this});p.register("responsive.index()",
function(a){a=d(a);return{column:a.data("dtr-index"),row:a.parent().data("dtr-index")}});p.register("responsive.rebuild()",function(){return this.iterator("table",function(a){a._responsive&&a._responsive._classLogic()})});p.register("responsive.recalc()",function(){return this.iterator("table",function(a){a._responsive&&(a._responsive._resizeAuto(),a._responsive._resize())})});p.register("responsive.hasHidden()",function(){var a=this.context[0];return a._responsive?-1!==d.inArray(!1,a._responsive.s.current):
!1});p.registerPlural("columns().responsiveHidden()","column().responsiveHidden()",function(){return this.iterator("column",function(a,b){return a._responsive?a._responsive.s.current[b]:!1},1)});i.version="2.2.2";d.fn.dataTable.Responsive=i;d.fn.DataTable.Responsive=i;d(j).on("preInit.dt.dtr",function(a,b){if("dt"===a.namespace&&(d(b.nTable).hasClass("responsive")||d(b.nTable).hasClass("dt-responsive")||b.oInit.responsive||o.defaults.responsive)){var c=b.oInit.responsive;!1!==c&&new i(b,d.isPlainObject(c)?
c:{})}});return i});


/*!
 RowReorder 1.2.4
 2015-2018 SpryMedia Ltd - datatables.net/license
*/
(function(d){"function"===typeof define&&define.amd?define(["jquery","datatables.net"],function(f){return d(f,window,document)}):"object"===typeof exports?module.exports=function(f,g){f||(f=window);if(!g||!g.fn.dataTable)g=require("datatables.net")(f,g).$;return d(g,f,f.document)}:d(jQuery,window,document)})(function(d,f,g,m){var h=d.fn.dataTable,k=function(c,b){if(!h.versionCheck||!h.versionCheck("1.10.8"))throw"DataTables RowReorder requires DataTables 1.10.8 or newer";this.c=d.extend(!0,{},h.defaults.rowReorder,
k.defaults,b);this.s={bodyTop:null,dt:new h.Api(c),getDataFn:h.ext.oApi._fnGetObjectDataFn(this.c.dataSrc),middles:null,scroll:{},scrollInterval:null,setDataFn:h.ext.oApi._fnSetObjectDataFn(this.c.dataSrc),start:{top:0,left:0,offsetTop:0,offsetLeft:0,nodes:[]},windowHeight:0,documentOuterHeight:0,domCloneOuterHeight:0};this.dom={clone:null,dtScroll:d("div.dataTables_scrollBody",this.s.dt.table().container())};var a=this.s.dt.settings()[0],e=a.rowreorder;if(e)return e;a.rowreorder=this;this._constructor()};
d.extend(k.prototype,{_constructor:function(){var c=this,b=this.s.dt,a=d(b.table().node());"static"===a.css("position")&&a.css("position","relative");d(b.table().container()).on("mousedown.rowReorder touchstart.rowReorder",this.c.selector,function(a){if(c.c.enable){if(d(a.target).is(c.c.excludedChildren))return!0;var i=d(this).closest("tr"),j=b.row(i);if(j.any())return c._emitEvent("pre-row-reorder",{node:j.node(),index:j.index()}),c._mouseDown(a,i),!1}});b.on("destroy.rowReorder",function(){d(b.table().container()).off(".rowReorder");
b.off(".rowReorder")})},_cachePositions:function(){var c=this.s.dt,b=d(c.table().node()).find("thead").outerHeight(),a=d.unique(c.rows({page:"current"}).nodes().toArray()),e=d.map(a,function(a){return d(a).position().top-b}),a=d.map(e,function(a,b){return e.length<b-1?(a+e[b+1])/2:(a+a+d(c.row(":last-child").node()).outerHeight())/2});this.s.middles=a;this.s.bodyTop=d(c.table().body()).offset().top;this.s.windowHeight=d(f).height();this.s.documentOuterHeight=d(g).outerHeight()},_clone:function(c){var b=
d(this.s.dt.table().node().cloneNode(!1)).addClass("dt-rowReorder-float").append("<tbody/>").append(c.clone(!1)),a=c.outerWidth(),e=c.outerHeight(),i=c.children().map(function(){return d(this).width()});b.width(a).height(e).find("tr").children().each(function(a){this.style.width=i[a]+"px"});b.appendTo("body");this.dom.clone=b;this.s.domCloneOuterHeight=b.outerHeight()},_clonePosition:function(c){var b=this.s.start,a=this._eventToPage(c,"Y")-b.top,c=this._eventToPage(c,"X")-b.left,e=this.c.snapX,a=
a+b.offsetTop,b=!0===e?b.offsetLeft:"number"===typeof e?b.offsetLeft+e:c+b.offsetLeft;0>a?a=0:a+this.s.domCloneOuterHeight>this.s.documentOuterHeight&&(a=this.s.documentOuterHeight-this.s.domCloneOuterHeight);this.dom.clone.css({top:a,left:b})},_emitEvent:function(c,b){this.s.dt.iterator("table",function(a){d(a.nTable).triggerHandler(c+".dt",b)})},_eventToPage:function(c,b){return-1!==c.type.indexOf("touch")?c.originalEvent.touches[0]["page"+b]:c["page"+b]},_mouseDown:function(c,b){var a=this,e=this.s.dt,
i=this.s.start,j=b.offset();i.top=this._eventToPage(c,"Y");i.left=this._eventToPage(c,"X");i.offsetTop=j.top;i.offsetLeft=j.left;i.nodes=d.unique(e.rows({page:"current"}).nodes().toArray());this._cachePositions();this._clone(b);this._clonePosition(c);this.dom.target=b;b.addClass("dt-rowReorder-moving");d(g).on("mouseup.rowReorder touchend.rowReorder",function(b){a._mouseUp(b)}).on("mousemove.rowReorder touchmove.rowReorder",function(b){a._mouseMove(b)});d(f).width()===d(g).width()&&d(g.body).addClass("dt-rowReorder-noOverflow");
e=this.dom.dtScroll;this.s.scroll={windowHeight:d(f).height(),windowWidth:d(f).width(),dtTop:e.length?e.offset().top:null,dtLeft:e.length?e.offset().left:null,dtHeight:e.length?e.outerHeight():null,dtWidth:e.length?e.outerWidth():null}},_mouseMove:function(c){this._clonePosition(c);for(var b=this._eventToPage(c,"Y")-this.s.bodyTop,a=this.s.middles,e=null,i=this.s.dt,j=i.table().body(),g=0,f=a.length;g<f;g++)if(b<a[g]){e=g;break}null===e&&(e=a.length);if(null===this.s.lastInsert||this.s.lastInsert!==
e)0===e?this.dom.target.prependTo(j):(b=d.unique(i.rows({page:"current"}).nodes().toArray()),e>this.s.lastInsert?this.dom.target.insertAfter(b[e-1]):this.dom.target.insertBefore(b[e])),this._cachePositions(),this.s.lastInsert=e;this._shiftScroll(c)},_mouseUp:function(){var c=this,b=this.s.dt,a,e,i=this.c.dataSrc;this.dom.clone.remove();this.dom.clone=null;this.dom.target.removeClass("dt-rowReorder-moving");d(g).off(".rowReorder");d(g.body).removeClass("dt-rowReorder-noOverflow");clearInterval(this.s.scrollInterval);
this.s.scrollInterval=null;var j=this.s.start.nodes,f=d.unique(b.rows({page:"current"}).nodes().toArray()),k={},h=[],l=[],n=this.s.getDataFn,m=this.s.setDataFn;a=0;for(e=j.length;a<e;a++)if(j[a]!==f[a]){var o=b.row(f[a]).id(),s=b.row(f[a]).data(),p=b.row(j[a]).data();o&&(k[o]=n(p));h.push({node:f[a],oldData:n(s),newData:n(p),newPosition:a,oldPosition:d.inArray(f[a],j)});l.push(f[a])}var q=[h,{dataSrc:i,nodes:l,values:k,triggerRow:b.row(this.dom.target)}];this._emitEvent("row-reorder",q);var r=function(){if(c.c.update){a=
0;for(e=h.length;a<e;a++){var d=b.row(h[a].node).data();m(d,h[a].newData);b.columns().every(function(){this.dataSrc()===i&&b.cell(h[a].node,this.index()).invalidate("data")})}c._emitEvent("row-reordered",q);b.draw(!1)}};this.c.editor?(this.c.enable=!1,this.c.editor.edit(l,!1,d.extend({submit:"changed"},this.c.formOptions)).multiSet(i,k).one("preSubmitCancelled.rowReorder",function(){c.c.enable=!0;c.c.editor.off(".rowReorder");b.draw(!1)}).one("submitUnsuccessful.rowReorder",function(){b.draw(!1)}).one("submitSuccess.rowReorder",
function(){r()}).one("submitComplete",function(){c.c.enable=!0;c.c.editor.off(".rowReorder")}).submit()):r()},_shiftScroll:function(c){var b=this,a=this.s.scroll,e=!1,d=c.pageY-g.body.scrollTop,f,h;65>d?f=-5:d>a.windowHeight-65&&(f=5);null!==a.dtTop&&c.pageY<a.dtTop+65?h=-5:null!==a.dtTop&&c.pageY>a.dtTop+a.dtHeight-65&&(h=5);f||h?(a.windowVert=f,a.dtVert=h,e=!0):this.s.scrollInterval&&(clearInterval(this.s.scrollInterval),this.s.scrollInterval=null);!this.s.scrollInterval&&e&&(this.s.scrollInterval=
setInterval(function(){if(a.windowVert)g.body.scrollTop=g.body.scrollTop+a.windowVert;if(a.dtVert){var c=b.dom.dtScroll[0];if(a.dtVert)c.scrollTop=c.scrollTop+a.dtVert}},20))}});k.defaults={dataSrc:0,editor:null,enable:!0,formOptions:{},selector:"td:first-child",snapX:!1,update:!0,excludedChildren:"a"};var l=d.fn.dataTable.Api;l.register("rowReorder()",function(){return this});l.register("rowReorder.enable()",function(c){c===m&&(c=!0);return this.iterator("table",function(b){b.rowreorder&&(b.rowreorder.c.enable=
c)})});l.register("rowReorder.disable()",function(){return this.iterator("table",function(c){c.rowreorder&&(c.rowreorder.c.enable=!1)})});k.version="1.2.4";d.fn.dataTable.RowReorder=k;d.fn.DataTable.RowReorder=k;d(g).on("init.dt.dtr",function(c,b){if("dt"===c.namespace){var a=b.oInit.rowReorder,e=h.defaults.rowReorder;if(a||e)e=d.extend({},a,e),!1!==a&&new k(b,e)}});return k});


/*!
 * bootstrap-limit.js 0.1.0
 * https://github.com/trongrg/bootstrap-limit
 * Copyright 2013 TrongTran and other contributors; Licensed MIT
 */

var VERSION = "0.1.0";

x = !function($) {	// Mitsukibo
    "use strict";
    var BootstrapLimit = function(element, options) {
        this.$element = $(element);
        this.options = $.extend({}, $.fn.limit.defaults, {
            maxLength: parseInt(this.$element.attr("maxlength"), 10)
        }, options);
        this.$counter = $(this.options.counter) || this.$counter;
        this.initialize();
    };
    BootstrapLimit.prototype = {
        constructor: BootstrapLimit,
        initialize: function() {
            this.initEvents();
            this.update();
            if (this.options.removeMaxLengthAttr) {
                this.$element.removeAttr("maxlength");
            }
            var self = this;
            $.when.apply($, this.$element.get()).always(function() {
                setTimeout(function() {
                    self.$element.trigger("bootstrap-limit:initialized");
                }, 0);
            });
        },
        initEvents: function() {
            this.$element.on("keypress", $.proxy(this.keypress, this)).on("keyup", $.proxy(this.keyup, this));
        },
        update: function() {
            this.query = this.$element.val();
            this.$counter.text(this.options.maxLength - this.query.length);
            if (this.query.length > this.options.maxLength) {
                this.$counter.css("color", this.options.color);
                this.$element.trigger("bootstrap-limit:crossed");
            } else if (this.query.length > this.options.maxLength - this.options.threshold) {
                this.$counter.css("color", this.options.color);
                this.$element.trigger("bootstrap-limit:uncrossed");
            } else {
                this.$counter.removeAttr("style");
                this.$element.trigger("bootstrap-limit:uncrossed");
            }
        },
        keyup: function(e) {
            this.update();
            e.stopPropagation();
            e.preventDefault();
        },
        keypress: function(e) {
            this.update();
            e.stopPropagation();
        }
    };
    $.fn.limit = function(option) {
        return this.each(function() {
            var $this = $(this), data = $this.data("bootstrap-limit"), options = typeof option == "object" && option;
            if (!data) {
                $this.data("bootstrap-limit", data = new BootstrapLimit(this, options));
            }
            if (typeof option == "string") {
                data[option]();
            }
        });
    };
    $.fn.limit.defaults = {
        maxLength: 140,
        color: "red",
        threshold: 10,
        counter: "",
        removeMaxLengthAttr: false
    };
    $.fn.limit.Constructor = BootstrapLimit;
    $(function() {
        $("body").on("focus.bootstrap-limit.data-api", '[data-provide="bootstrap-limit"]', function(e) {
            var $this = $(this);
            if ($this.data("bootstrap-limit")) {
                return;
            }
            e.preventDefault();
            $this.limit($this.data());
        });
    });
}(window.jQuery);