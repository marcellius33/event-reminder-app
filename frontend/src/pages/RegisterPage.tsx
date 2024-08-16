import EmailIcon from "@mui/icons-material/Email";
import LockIcon from "@mui/icons-material/Lock";
import "../css/register-page.css";
import { useNavigate } from "react-router-dom";
import AccountCircleIcon from "@mui/icons-material/AccountCircle";

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
      <div className="inputs">
        <div className="input">
          <AccountCircleIcon className="icon" />
          <input type="text" placeholder="Name" />
        </div>
        <div className="input">
          <EmailIcon className="icon" />
          <input type="email" placeholder="Email" />
        </div>
        <div className="input">
          <LockIcon className="icon" />
          <input type="password" placeholder="Password" />
        </div>
        <div className="input">
          <LockIcon className="icon" />
          <input type="password" placeholder="Confirm Password" />
        </div>
      </div>
      <div className="login-page">
        Already have an account?
        <span onClick={handleLoginClick}> Click Here!</span>
      </div>
      <div className="submit-container">
        <div className="submit">Register</div>
      </div>
    </div>
  );
}

export default RegisterPage;
