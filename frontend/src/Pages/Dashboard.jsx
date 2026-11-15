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
import StudentsPanel from "../Features/Students/StudentsPanel";
import SubjectsPanel from "../Features/Subjects/SubjectsPanel";
import TeachersPanel from "../Features/Teachers/TeachersPanel";
import LogsPanel from "../Features/Logs/LogsPanel";
export default function DashboardPage() {
  const [activeTab, setActiveTab] = useState(1);
  const currentTab = tabs[activeTab];

  return (
    <Box sx={{ minHeight: "100vh", py: { xs: 3, md: 6 } }}>
      <Container maxWidth="xl">
        <Stack spacing={1} sx={{ mb: 4 }}>
          <Chip
            label="Management Dashboard"
            color="primary"
            variant="outlined"
            sx={{ width: "fit-content" }}
          />

          <Typography variant="h4" component="h1" fontWeight={800}>
            EduCore
          </Typography>

          <Typography color="text.secondary">
            Student, teacher, and subject management system
          </Typography>
        </Stack>

        <Paper
          sx={{
            border: "1px solid",
            borderColor: "divider",
            borderRadius: 3,
            overflow: "hidden",
          }}
        >
          <Box
            sx={{
              borderBottom: 1,
              borderColor: "divider",
              px: { xs: 1, md: 3 },
            }}
          >
            <Tabs
              value={activeTab}
              onChange={(_, value) => setActiveTab(value)}
              variant="scrollable"
              scrollButtons="auto"
            >
              {tabs.map((tab) => (
                <Tab
                  key={tab.label}
                  label={tab.label}
                  icon={tab.icon}
                  iconPosition="start"
                  sx={{ textTransform: "none", minHeight: 68 }}
                />
              ))}
            </Tabs>
          </Box>

          <Box sx={{ p: { xs: 2, md: 4 }, minHeight: 350 }}>
            <Stack spacing={1} sx={{ mb: 3 }}>
              <Typography variant="h5" fontWeight={700}>
                {currentTab.title}
              </Typography>

              <Typography color="text.secondary">
                {currentTab.description}
              </Typography>
            </Stack>

            {activeTab === 0 && <SubjectsPanel />}
            {activeTab === 1 && <StudentsPanel />}
            {activeTab === 2 && <TeachersPanel />}
            {activeTab === 3 && <LogsPanel />}
          </Box>
        </Paper>
      </Container>
    </Box>
  );
}
