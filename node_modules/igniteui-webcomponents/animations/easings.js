var EaseIn;
(function (EaseIn) {
    EaseIn["Quad"] = "cubic-bezier(0.550, 0.085, 0.680, 0.530)";
    EaseIn["Cubic"] = "cubic-bezier(0.550, 0.055, 0.675, 0.190)";
    EaseIn["Quart"] = "cubic-bezier(0.895, 0.030, 0.685, 0.220)";
    EaseIn["Quint"] = "cubic-bezier(0.755, 0.050, 0.855, 0.060)";
    EaseIn["Sine"] = "cubic-bezier(0.470, 0.000, 0.745, 0.715)";
    EaseIn["Expo"] = "cubic-bezier(0.950, 0.050, 0.795, 0.035)";
    EaseIn["Circ"] = "cubic-bezier(0.600, 0.040, 0.980, 0.335)";
    EaseIn["Back"] = "cubic-bezier(0.600, -0.280, 0.735, 0.045)";
})(EaseIn || (EaseIn = {}));
var EaseOut;
(function (EaseOut) {
    EaseOut["Quad"] = "cubic-bezier(0.250, 0.460, 0.450, 0.940)";
    EaseOut["Cubic"] = "cubic-bezier(0.215, 0.610, 0.355, 1.000)";
    EaseOut["Quart"] = "cubic-bezier(0.165, 0.840, 0.440, 1.000)";
    EaseOut["Quint"] = "cubic-bezier(0.230, 1.000, 0.320, 1.000)";
    EaseOut["Sine"] = "cubic-bezier(0.390, 0.575, 0.565, 1.000)";
    EaseOut["Expo"] = "cubic-bezier(0.190, 1.000, 0.220, 1.000)";
    EaseOut["Circ"] = "cubic-bezier(0.075, 0.820, 0.165, 1.000)";
    EaseOut["Back"] = "cubic-bezier(0.175, 0.885, 0.320, 1.275)";
})(EaseOut || (EaseOut = {}));
var EaseInOut;
(function (EaseInOut) {
    EaseInOut["Quad"] = "cubic-bezier(0.455, 0.030, 0.515, 0.955)";
    EaseInOut["Cubic"] = "cubic-bezier(0.645, 0.045, 0.355, 1.000)";
    EaseInOut["Quart"] = "cubic-bezier(0.770, 0.000, 0.175, 1.000)";
    EaseInOut["Quint"] = "cubic-bezier(0.860, 0.000, 0.070, 1.000)";
    EaseInOut["Sine"] = "cubic-bezier(0.445, 0.050, 0.550, 0.950)";
    EaseInOut["Expo"] = "cubic-bezier(1.000, 0.000, 0.000, 1.000)";
    EaseInOut["Circ"] = "cubic-bezier(0.785, 0.135, 0.150, 0.860)";
    EaseInOut["Back"] = "cubic-bezier(0.680, -0.550, 0.265, 1.550)";
})(EaseInOut || (EaseInOut = {}));
export { EaseIn, EaseOut, EaseInOut };
//# sourceMappingURL=easings.js.map