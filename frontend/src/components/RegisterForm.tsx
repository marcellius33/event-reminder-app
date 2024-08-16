import * as Yup from "yup";
import AccountCircleIcon from "@mui/icons-material/AccountCircle";
import EmailIcon from "@mui/icons-material/Email";
import LockIcon from "@mui/icons-material/Lock";
import { useNavigate } from "react-router-dom";
import { useState } from "react";
import { useRegister } from "../api-hooks/auth/mutation";

const registerValidationSchema = Yup.object({
  name: Yup.string().required("Name is required"),
  email: Yup.string()
    .email("Invalid email address")
    .required("Email is required"),
  password: Yup.string()
    .min(6, "Password must be at least 6 characters")
    .required("Password is required"),
  passwordConfirmation: Yup.string()
    .oneOf([Yup.ref("password"), ""], "Passwords must match")
    .required("Confirm Password is required"),
});

function RegisterForm() {
  const navigate = useNavigate();
  const [formValues, setFormValues] = useState({
    name: "",
    email: "",
    password: "",
    passwordConfirmation: "",
  });
  const [errors, setErrors] = useState<{ [key: string]: string }>({});
  const [touched, setTouched] = useState<{ [key: string]: boolean }>({});

  const handleInputChange = (e: React.ChangeEvent<HTMLInputElement>) => {
    const { name, value } = e.target;
    setFormValues((prev) => ({ ...prev, [name]: value }));
  };

  const handleBlur = (e: React.FocusEvent<HTMLInputElement>) => {
    const { name } = e.target;
    setTouched((prev) => ({ ...prev, [name]: true }));
  };

  const validate = async () => {
    try {
      await registerValidationSchema.validate(formValues, {
        abortEarly: false,
      });
      setErrors({});

      return true;
    } catch (err) {
      if (err instanceof Yup.ValidationError) {
        const validationErrors: { [key: string]: string } = {};
        err.inner.forEach((error) => {
          if (error.path) {
            validationErrors[error.path] = error.message;
          }
        });

        setErrors(validationErrors);
      }
      return false;
    }
  };

  const { mutateAsync: registerAsync } = useRegister();

  const handleSubmit = async (e: React.FormEvent<HTMLFormElement>) => {
    e.preventDefault();

    const isValid = await validate();
    if (isValid) {
      try {
        await registerAsync(formValues);

        navigate("/");
      } catch (e) {
        // TODO: error handling
        console.log(e);
      }
    }
  };

  return (
    <form onSubmit={handleSubmit} className="inputs">
      <div className="input">
        <AccountCircleIcon className="icon" />
        <input
          type="text"
          name="name"
          placeholder="Name"
          value={formValues.name}
          onChange={handleInputChange}
          onBlur={handleBlur}
        />
      </div>
      {touched.name && errors.name && (
        <div className="error-message">{errors.name}</div>
      )}
      <div className="input">
        <EmailIcon className="icon" />
        <input
          type="email"
          name="email"
          placeholder="Email"
          value={formValues.email}
          onChange={handleInputChange}
          onBlur={handleBlur}
        />
      </div>
      {touched.email && errors.email && (
        <div className="error-message">{errors.email}</div>
      )}
      <div className="input">
        <LockIcon className="icon" />
        <input
          type="password"
          name="password"
          placeholder="Password"
          value={formValues.password}
          onChange={handleInputChange}
          onBlur={handleBlur}
        />
      </div>
      {touched.password && errors.password && (
        <div className="error-message">{errors.password}</div>
      )}
      <div className="input">
        <LockIcon className="icon" />
        <input
          type="password"
          name="passwordConfirmation"
          placeholder="Confirm Password"
          value={formValues.passwordConfirmation}
          onChange={handleInputChange}
          onBlur={handleBlur}
        />
      </div>
      {touched.passwordConfirmation && errors.passwordConfirmation && (
        <div className="error-message">{errors.passwordConfirmation}</div>
      )}
      <div className="submit-container">
        <button type="submit" className="submit">
          Register
        </button>
      </div>
    </form>
  );
}

export default RegisterForm;
