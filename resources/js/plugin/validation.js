import {
  Form as VeeForm,
  Field as VeeField,
  defineRule,
  configure,
  ErrorMessage,
} from "vee-validate";
import {
  required,
  min,
  max,
  alpha_spaces as alphaSpaces,
  alpha_num as alphaNum,
  alpha,
  email,
  min_value as minVal,
  max_value as maxVal,
  not_one_of as excluded,
  confirmed, regex as regex_exp,
  numeric, url
} from "@vee-validate/rules";

export default {
  install(app) {
    app.component("VeeForm", VeeForm);
    app.component("VeeField", VeeField);
    app.component("ErrorMessage", ErrorMessage);

    defineRule("required", required);
    defineRule("min", min);
    defineRule("max", max);
    defineRule("alpha", alpha);
    defineRule("alpha_spaces", alphaSpaces);
    defineRule("alpha_num", alphaNum);
    defineRule("email", email);
    defineRule("min_value", minVal);
    defineRule("max_value", maxVal);
    defineRule("excluded", excluded);
    defineRule("country_excluded", excluded);
    defineRule("password_mismatch", confirmed);
    defineRule("numeric", numeric);
    defineRule("url", url);
    defineRule("regex", regex_exp);
    configure({
      generateMessage: (context) => {
        const messages = {
          required: `This field is required.`,
          regex: `This field is invalid.`,
          min: `The value of this field is too short.`,
          max: `The value of this field is too long.`,
          alpha_spaces: `This field can only contain letters and spaces.`,
          email: `This is not a valid email.`,
          min_value: `This field is too low.`,
          max_value: `This field is too high.`,
          excluded: "This field is not allowed.",
          country_excluded: "We do not allow users from this location",
          password_mismatch: `The confirm password field does not match.`,
        };
        const message = messages[context.rule.name]
          ? messages[context.rule.name]
          : `This field is invalid.`;
        return message;
      },
      bails: false,
      validateOnBlur: false,
      validateOnChange: false,
      validateOnInput: false,
      validateOnModelUpdate: false,
    });
  },
};