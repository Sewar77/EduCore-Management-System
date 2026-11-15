import { useState } from "react";

import {
  Box,
  Chip,
  Container,
  Paper,
  Stack,
  Tab,
  Tabs,
  Typography,
} from "@mui/material";
import {
  MenuBookOutlined,
  People,
  SchoolOutlined,
  HistoryOutlined,
} from "@mui/icons-material";
const tabs = [
  {
    label: "Subjects",
    icon: <MenuBookOutlined />,
    title: "Subjects Management",
    description: "Manage subjects and their information.",
  },
  {
    label: "Students",
    icon: <People />,
    title: "Students Management",
    description: "Manage student records and personal information.",
  },
  {
    label: "Teachers",
    icon: <SchoolOutlined />,
    title: "Teachers Management",
    description: "Manage teachers and their assigned subjects.",
  },
  {
    label: "Activity Logs",
    icon: <HistoryOutlined />,
    title: "Activity Logs",
    description: "View changes made to records in the system.",
  },
];

export default function DashboardPage() {
  const [activeTab, setActiveTab] = useState(0);

  const currentTab = tabs[activeTab];

  return (
    <Box sx={{ minHeight: "100vh", py: { xs: 3, md: 6 } }}>
      <Container maxWidth="xl">
        {/* Header */}
        <Stack
          sx={{
            gap: 1,
            mb: 4,
          }}
        >
          <Chip
            label="Management Dashboard"
            color="primary"
            variant="outlined"
            sx={{ width: "fit-content" }}
          />

          <Typography component="h1" variant="h4" sx={{ fontWeight: 800 }}>
            EduCore
          </Typography>

          <Typography color="text.secondary">
            Student, teacher, and subject management system
          </Typography>
        </Stack>

        {/* Main content */}
        <Paper
          sx={{
            border: "1px solid",
            borderColor: "divider",
            borderRadius: 3,
            overflow: "hidden",
          }}
        >
          {/* Tabs */}
          <Box
            sx={{
              borderBottom: "1px solid",
              borderColor: "divider",
              px: { xs: 1, md: 3 },
            }}
          >
            <Tabs
              value={activeTab}
              onChange={(_, newValue) => {
                setActiveTab(newValue);
              }}
              variant="scrollable"
              scrollButtons="auto"
            >
              {tabs.map((tab, index) => (
                <Tab
                  key={tab.label}
                  id={`educore-tab-${index}`}
                  aria-controls={`educore-panel-${index}`}
                  icon={tab.icon}
                  iconPosition="start"
                  label={tab.label}
                  sx={{ textTransform: "none", minHeight: 68 }}
                />
              ))}
            </Tabs>
          </Box>

          {/* Active tab content */}
          <Box
            role="tabpanel"
            id={`educore-panel-${activeTab}`}
            aria-labelledby={`educore-tab-${activeTab}`}
            sx={{
              p: { xs: 2, md: 4 },
              minHeight: 350,
            }}
          >
            <Stack sx={{ gap: 1, mb: 4 }}>
              <Typography variant="h5" sx={{ fontWeight: 700 }}>
                {currentTab.title}
              </Typography>

              <Typography color="text.secondary">
                {currentTab.description}
              </Typography>
            </Stack>

            <Box
              sx={{
                border: "1px dashed",
                borderColor: "divider",
                borderRadius: 3,
                p: 5,
                textAlign: "center",
              }}
            >
              <Typography color="text.secondary">
                {currentTab.label} content will appear here.
              </Typography>
            </Box>
          </Box>
        </Paper>
      </Container>
    </Box>
  );
}
