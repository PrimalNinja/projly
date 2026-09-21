"use strict";
(() => {
  // src/slick.formatters.ts
  var Utils = Slick.Utils, PercentCompleteFormatter = (_row, _cell, value) => !Utils.isDefined(value) || value === "" ? "-" : value < 50 ? `<span style="color:red;font-weight:bold;">${value}%</span>` : `<span style="color:green">${value}%</span>`, PercentCompleteBarFormatter = (_row, _cell, value) => {
    if (!Utils.isDefined(value) || value === "")
      return "";
    let color;
    return value < 30 ? color = "red" : value < 70 ? color = "silver" : color = "green", `<span class="percent-complete-bar" style="background:${color};width:${value}%" title="${value}%"></span>`;
  }, DeleteButtonFormatter = (_row, _cell, value) => '<button class="btn btn-primary gb-button glyphicon glyphicon-trash" style="text-align:center; width:100%; height:100%; margin:0 0 0 0; padding:0 0 0 0;"></button>', YesNoFormatter = (_row, _cell, value) => value ? "Yes" : "No", CheckboxFormatter = (_row, _cell, value) => `<span class="sgi sgi-checkbox-${value ? "intermediate" : "blank-outline"}"></span>`, CheckmarkFormatter = (_row, _cell, value) => value ? '<span class="sgi sgi-check"></span>' : "", Formatters = {
    PercentComplete: PercentCompleteFormatter,
    PercentCompleteBar: PercentCompleteBarFormatter,
    YesNo: YesNoFormatter,
	DeleteButton: DeleteButtonFormatter,	// Mitsukibo: new delete button editor
    Checkmark: CheckmarkFormatter,
    Checkbox: CheckboxFormatter
  };
  window.Slick && Utils.extend(Slick, {
    Formatters
  });
})();
//# sourceMappingURL=slick.formatters.js.map
