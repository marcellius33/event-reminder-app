import "../css/login-page.css";
import { useNavigate } from "react-router-dom";
import LoginForm from "../components/LoginForm";
import { getTokenStorage } from "../common/utils/storage";
import { useEffect } from "react";

function LoginPage() {
  const navigate = useNavigate();
  const authenticated = getTokenStorage() !== null;

  const handleRegisterClick = () => {
    navigate("/register");
  };

  useEffect(() => {
    if (authenticated) {
      navigate("/dashboard");
    }
  });

  return (
    <div className="container">
      <div className="header">
        <div className="text">Login</div>
        <div className="underline"></div>
      </div>
      <LoginForm />
      <div className="register-page">
        Don't have an account yet?
        <span onClick={handleRegisterClick}> Click Here!</span>
      </div>
    </div>
  );
}

export default LoginPage;
