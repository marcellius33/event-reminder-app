import "../css/register-page.css";
import { useNavigate } from "react-router-dom";
import RegisterForm from "../components/RegisterForm";

function RegisterPage() {
  const navigate = useNavigate();

  const handleLoginClick = () => {
    navigate("/");
  };

  return (
    <div className="container">
      <div className="header">
        <div className="text">Register</div>
        <div className="underline"></div>
      </div>
      <RegisterForm />
      <div className="login-page">
        Already have an account?
        <span onClick={handleLoginClick}> Click Here!</span>
      </div>
    </div>
  );
}

export default RegisterPage;
