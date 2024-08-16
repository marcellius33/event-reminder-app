import EmailIcon from "@mui/icons-material/Email";
import LockIcon from "@mui/icons-material/Lock";
import "../css/login-page.css";
import { useNavigate } from "react-router-dom";

function LoginPage() {
  const navigate = useNavigate();

  const handleRegisterClick = () => {
    navigate("/register");
  };

  return (
    <div className="container">
      <div className="header">
        <div className="text">Login</div>
        <div className="underline"></div>
      </div>
      <div className="inputs">
        <div className="input">
          <EmailIcon className="icon" />
          <input type="email" placeholder="Email" />
        </div>
        <div className="input">
          <LockIcon className="icon" />
          <input type="password" placeholder="Password" />
        </div>
      </div>
      <div className="register-page">
        Don't have an account yet?
        <span onClick={handleRegisterClick}> Click Here!</span>
      </div>
      <div className="submit-container">
        <div className="submit">Login</div>
      </div>
    </div>
  );
}

export default LoginPage;
