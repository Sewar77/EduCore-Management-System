import { createTheme } from "@mui/material/styles";

const theme = createTheme({
  palette: {
    mode: "light",
    primary: {
      main: "#4F46E5",
    },
    background: {
      default: "#F6F7FB",
      paper: "#FFFFFF",
    },
    text: {
      primary: "#172033",
      secondary: "#64748B",
    },
  },

  typography: {
    fontFamily: "'Segoe UI', Arial, sans-serif",
  },

  shape: {
    borderRadius: 12,
  },

  components: {
    MuiButton: {
      styleOverrides: {
        root: {
          textTransform: "none",
          fontWeight: 600,
          borderRadius: 9,
        },
      },
    },

    MuiPaper: {
      defaultProps: {
        elevation: 0,
      },
    },

    MuiTextField: {
      defaultProps: {
        size: "small",
      },
    },
  },
});

export default theme;