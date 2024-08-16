import EmailIcon from "@mui/icons-material/Email";
import LockIcon from "@mui/icons-material/Lock";
import { useState } from "react";
import { useNavigate } from "react-router-dom";
import * as Yup from "yup";
import { useLogin } from "../api-hooks/auth/mutation";
import { setTokenStorage } from "../common/utils/storage";

const loginValidationSchema = Yup.object({
  email: Yup.string()
    .email("Invalid email address")
    .required("Email is required"),
  password: Yup.string()
    .min(6, "Password must be at least 6 characters")
    .required("Password is required"),
});

function LoginForm() {
  const navigate = useNavigate();
  const [formValues, setFormValues] = useState({ email: "", password: "" });
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
      await loginValidationSchema.validate(formValues, { abortEarly: false });
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

  const { mutateAsync: loginAsync } = useLogin();

  const handleSubmit = async (e: React.FormEvent<HTMLFormElement>) => {
    e.preventDefault();

    const isValid = await validate();
    if (isValid) {
      try {
        const result = await loginAsync(formValues);
        const data = { ...result.data };
        setTokenStorage(data);

        navigate("/event");
      } catch (e) {
        // TODO: error handling
        console.log(e);
      }
    }
  };

  return (
    <form onSubmit={handleSubmit} className="inputs">
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
      <div className="submit-container">
        <button type="submit" className="submit">
          Login
        </button>
      </div>
    </form>
  );
}

export default LoginForm;
